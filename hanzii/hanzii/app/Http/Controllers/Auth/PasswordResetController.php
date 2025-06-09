<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dungs',
        ], [
            'email.exists' => 'Không tìm thấy tài khoản với địa chỉ email này.',
        ]);

        // Tạo token
        $token = Str::random(64);
        $email = $request->email;

        // Xóa các token cũ
        PasswordReset::where('email', $email)->delete();

        // Lưu token mới
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Tạo link đặt lại mật khẩu
        $resetLink = route('password.reset', ['token' => $token, 'email' => $email]);

        // Gửi email
        Mail::send('emails.reset-password', ['resetLink' => $resetLink], function ($message) use ($email) {
            $message->to($email)
                ->subject('Đặt lại mật khẩu');
        });

        return back()->with('status', 'Chúng tôi đã gửi email liên kết đặt lại mật khẩu!');
    }

    /**
     * Hiển thị form đặt lại mật khẩu
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->email;
        $passwordReset = PasswordReset::where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Kiểm tra thời gian tạo token (hết hạn sau 60 phút)
        $createdAt = Carbon::parse($passwordReset->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            PasswordReset::where('email', $email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu lại.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    /**
     * Đặt lại mật khẩu
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Kiểm tra thời gian tạo token (hết hạn sau 60 phút)
        $createdAt = Carbon::parse($passwordReset->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            PasswordReset::where('email', $request->email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Link đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu lại.']);
        }

        // Cập nhật mật khẩu
        $nguoiDung = NguoiDung::where('email', $request->email)->first();
        $nguoiDung->mat_khau = Hash::make($request->password);
        $nguoiDung->save();

        // Xóa token đã sử dụng
        PasswordReset::where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Mật khẩu đã được đặt lại thành công! Vui lòng đăng nhập bằng mật khẩu mới.');
    }
} 