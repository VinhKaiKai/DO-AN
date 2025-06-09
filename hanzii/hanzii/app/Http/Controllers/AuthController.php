<?php

namespace App\Http\Controllers;

use App\Models\HocVien;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use App\Models\GiaoVien;
use App\Models\TroGiang;
use App\Models\DangKyQuanTam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tìm người dùng theo email
        $nguoiDung = NguoiDung::where('email', $credentials['email'])->first();

        if (!$nguoiDung) {
            return back()->withErrors([
                'email' => 'Thông tin đăng nhập không chính xác.',
            ])->withInput($request->except('password'));
        }

        // Kiểm tra mật khẩu
        $passwordCorrect = Hash::check($credentials['password'], $nguoiDung->mat_khau);
        

        if ($passwordCorrect) {
            // Tạo session cho người dùng
            $request->session()->put('nguoi_dung_id', $nguoiDung->id);
            $request->session()->put('loai_tai_khoan', $nguoiDung->loai_tai_khoan);
            
            
            $vaiTro = $nguoiDung->vaiTro ? $nguoiDung->vaiTro->ten : null;
            $request->session()->put('vai_tro', $vaiTro);
            
            // Thêm thông tin cần thiết cho layout dashboard
            $request->session()->put('user_full_name', $nguoiDung->ho . ' ' . $nguoiDung->ten);
            
            // Thêm avatar nếu có
            if ($nguoiDung->anh_dai_dien) {
                $request->session()->put('anh_dai_dien', asset('storage/' . $nguoiDung->anh_dai_dien));
            }
            
            // Chuyển hướng dựa trên vai trò
            if ($vaiTro === 'admin') {
                Log::debug('Chuyển hướng đến trang admin');
                return redirect()->route('admin.dashboard');
            } elseif ($vaiTro === 'giao_vien') {
                Log::debug('Chuyển hướng đến trang giáo viên');
                return redirect()->route('giao-vien.dashboard');
            } elseif ($vaiTro === 'tro_giang') {
                Log::debug('Chuyển hướng đến trang trợ giảng');
                return redirect()->route('tro-giang.dashboard');
            } elseif ($vaiTro === 'hoc_vien' || $nguoiDung->loai_tai_khoan == 'hoc_vien') {
                Log::debug('Chuyển hướng đến trang học viên');
                return redirect()->route('hoc-vien.lop-hoc.index');
            } else {
                // Chuyển về welcome nếu không có vai trò cụ thể
                Log::debug('Không có vai trò cụ thể, chuyển về trang chủ');
                return redirect()->route('welcome')->with('error', 'Tài khoản không có vai trò cụ thể. Vui lòng liên hệ quản trị viên.');
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->except('password'));
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:nguoi_dungs',
            'so_dien_thoai' => 'required|string|max:20|unique:nguoi_dungs',
            'password' => 'required|string|min:6|confirmed',
            'dia_chi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tạo người dùng mới
        $nguoiDung = NguoiDung::create([
            'ho' => $request->ho,
            'ten' => $request->ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau' => Hash::make($request->password),
            'dia_chi' => $request->dia_chi,
            'loai_tai_khoan' => 'hoc_vien', // Mặc định là học viên
        ]);

        // Tạo hồ sơ học viên
        $hocVien = HocVien::create([
            'nguoi_dung_id' => $nguoiDung->id,
            'trang_thai' => 'hoat_dong',
        ]);

        // Tìm vai trò học viên
        $vaiTroHocVien = VaiTro::where('ten', 'hoc_vien')->first();
        if ($vaiTroHocVien) {
            // Gán vai trò học viên (quan hệ 1-n)
            $nguoiDung->vai_tro_id = $vaiTroHocVien->id;
            $nguoiDung->save();
        }

        // Đăng nhập người dùng sau khi đăng ký
        $request->session()->put('nguoi_dung_id', $nguoiDung->id);
        $request->session()->put('loai_tai_khoan', $nguoiDung->loai_tai_khoan);
        $request->session()->put('vai_tro', 'hoc_vien');

        return redirect()->route('hoc-vien.lop-hoc.index')->with('success', 'Đăng ký thành công!');
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'nguoi_dung_id', 
            'loai_tai_khoan', 
            'vai_tro', 
            'user_full_name', 
            'anh_dai_dien'
        ]);
        
        return redirect()->route('welcome')->with('success', 'Đăng xuất thành công!');
    }


  
}