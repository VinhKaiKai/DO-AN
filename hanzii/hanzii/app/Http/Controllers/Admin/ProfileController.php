<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin cá nhân của admin
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
        
        // Thêm mảng rỗng cho hoạt động gần đây (sẽ bổ sung sau)
        $recent_activities = [];
        
        return view('admin.profile.index', compact('nguoiDung', 'recent_activities'));
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
        
        return view('admin.profile.edit', compact('nguoiDung'));
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
            'anh_dai_dien' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Cập nhật thông tin người dùng
        $nguoiDung->ho = $request->ho;
        $nguoiDung->ten = $request->ten;
        $nguoiDung->email = $request->email;
        $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
        
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
        
        return redirect()->route('admin.profile.index')->with('success', 'Cập nhật thông tin thành công');
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
        
        $nguoiDung = NguoiDung::find($nguoiDungId);
        if (!$nguoiDung) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại');
        }
        
        return view('admin.profile.password', compact('nguoiDung'));
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
        
        return redirect()->route('admin.profile.index')->with('success', 'Đổi mật khẩu thành công');
    }
} 