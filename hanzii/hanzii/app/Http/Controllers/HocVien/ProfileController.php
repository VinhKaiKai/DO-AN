<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\NguoiDung;
use App\Models\DangKyHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin cá nhân của học viên
     */
    public function index()
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $nguoiDung = NguoiDung::find($nguoiDungId);
        if (!$nguoiDung) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy thống kê của học viên
        $tongSoKhoaHoc = DangKyHoc::where('hoc_vien_id', $hocVien->id)
                            ->where('trang_thai', 'da_xac_nhan')
                            ->count();
        
        $dangHoc = DangKyHoc::where('hoc_vien_id', $hocVien->id)
                    ->where('trang_thai', 'da_xac_nhan')
                    ->whereHas('lopHoc', function ($q) {
                        $q->where('trang_thai', 'dang_dien_ra');
                    })
                    ->count();
        
        $daHoanThanh = DangKyHoc::where('hoc_vien_id', $hocVien->id)
                        ->where('trang_thai', 'da_xac_nhan')
                        ->whereHas('lopHoc', function ($q) {
                            $q->where('trang_thai', 'da_hoan_thanh');
                        })
                        ->count();
        
        return view('hoc-vien.profile.index', compact(
            'nguoiDung', 
            'hocVien',
            'tongSoKhoaHoc',
            'dangHoc',
            'daHoanThanh'
        ));
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
        
        $nguoiDung = NguoiDung::find($nguoiDungId);
        if (!$nguoiDung) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        return view('hoc-vien.profile.edit', compact('nguoiDung', 'hocVien'));
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
        
        $nguoiDung = NguoiDung::find($nguoiDungId);
        if (!$nguoiDung) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Validate thông tin
        $request->validate([
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('nguoi_dungs')->ignore($nguoiDung->id),
            ],
            'so_dien_thoai' => [
                'required',
                'string',
                'regex:/^[0-9]{10}$/',
                Rule::unique('nguoi_dungs')->ignore($nguoiDung->id),
            ],
            'ngay_sinh' => 'required|date',
            'dia_chi' => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Cập nhật thông tin người dùng
        $nguoiDung->ho = $request->ho;
        $nguoiDung->ten = $request->ten;
        $nguoiDung->email = $request->email;
        $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
        $nguoiDung->dia_chi = $request->dia_chi;
        
        // Xử lý avatar nếu có
        if ($request->hasFile('anh_dai_dien')) {
            // Xóa ảnh cũ nếu có
            if ($nguoiDung->anh_dai_dien && $nguoiDung->anh_dai_dien !== 'avatars/default.png') {
                Storage::disk('public')->delete($nguoiDung->anh_dai_dien);
            }
            
            // Lưu ảnh mới
            $avatarPath = $request->file('anh_dai_dien')->store('avatars', 'public');
            $nguoiDung->anh_dai_dien = $avatarPath;
            
            // Cập nhật ảnh đại diện trong session
            $request->session()->put('anh_dai_dien', asset('storage/' . $avatarPath));
        }
        
        $nguoiDung->save();
        
        // Cập nhật thông tin học viên
        $hocVien->ngay_sinh = $request->ngay_sinh;
        $hocVien->save();
        
        return redirect()->route('hoc-vien.profile.index')->with('success', 'Cập nhật thông tin thành công');
    }
    
    /**
     * Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        return view('hoc-vien.profile.change-password');
    }
    
    /**
     * Cập nhật mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $nguoiDung = NguoiDung::find($nguoiDungId);
        if (!$nguoiDung) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại');
        }
        
        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $nguoiDung->mat_khau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }
        
        // Cập nhật mật khẩu mới
        $nguoiDung->mat_khau = Hash::make($request->password);
        $nguoiDung->save();
        
        return redirect()->route('hoc-vien.profile.index')->with('success', 'Đổi mật khẩu thành công');
    }
} 