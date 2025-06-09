<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiaoVien;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin cá nhân của giáo viên đăng nhập
     */
    public function index()
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $nguoiDung = NguoiDung::findOrFail($nguoiDungId);
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        return view('giao-vien.profile.index', compact('nguoiDung', 'giaoVien'));
    }
    
    /**
     * Hiển thị form chỉnh sửa thông tin cá nhân
     */
    public function edit()
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $nguoiDung = NguoiDung::findOrFail($nguoiDungId);
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        return view('giao-vien.profile.edit', compact('nguoiDung', 'giaoVien'));
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $validator = Validator::make($request->all(), [
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:nguoi_dungs,email,'.$nguoiDungId,
            'so_dien_thoai' => 'required|string|max:20|unique:nguoi_dungs,so_dien_thoai,'.$nguoiDungId,
            'dia_chi' => 'nullable|string|max:255',
            'bang_cap' => 'nullable|string',
            'chuyen_mon' => 'nullable|string|max:255',
            'so_nam_kinh_nghiem' => 'nullable|integer|min:0',
            'anh_dai_dien' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $nguoiDung = NguoiDung::findOrFail($nguoiDungId);
        $nguoiDung->ho = $request->ho;
        $nguoiDung->ten = $request->ten;
        $nguoiDung->email = $request->email;
        $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
        $nguoiDung->dia_chi = $request->dia_chi;
        
        // Xử lý avatar nếu có
        if ($request->hasFile('anh_dai_dien')) {
            // Xóa ảnh cũ nếu có
            if ($nguoiDung->anh_dai_dien) {
                $oldImagePath = storage_path('app/public/' . $nguoiDung->anh_dai_dien);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Lưu ảnh mới
            $anhDaiDien = $request->file('anh_dai_dien');
            $fileName = 'avatars/' . time() . '_' . $anhDaiDien->getClientOriginalName();
            $anhDaiDien->storeAs('public', $fileName);
            $nguoiDung->anh_dai_dien = $fileName;
            
            // Cập nhật ảnh đại diện trong session
            $request->session()->put('anh_dai_dien', asset('storage/' . $fileName));
        }
        
        $nguoiDung->save();
        
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if ($giaoVien) {
            // Xử lý bằng cấp từ chuỗi thành mảng (mỗi dòng là một phần tử)
            $bangCap = $request->bang_cap;
            if (!empty($bangCap)) {
                $bangCapArray = array_filter(preg_split('/\r\n|\r|\n/', $bangCap));
                $giaoVien->bang_cap = $bangCapArray;
            } else {
                $giaoVien->bang_cap = null;
            }
            
            $giaoVien->chuyen_mon = $request->chuyen_mon;
            $giaoVien->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
            $giaoVien->save();
        }
        
        return redirect()->route('giao-vien.profile.index')->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
    
    /**
     * Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        return view('giao-vien.profile.change-password');
    }
    
    /**
     * Cập nhật mật khẩu
     */
    public function changePassword(Request $request)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $nguoiDung = NguoiDung::findOrFail($nguoiDungId);
        
        if (!Hash::check($request->current_password, $nguoiDung->mat_khau)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }
        
        $nguoiDung->mat_khau = Hash::make($request->password);
        $nguoiDung->save();
        
        return redirect()->route('giao-vien.profile.index')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
} 