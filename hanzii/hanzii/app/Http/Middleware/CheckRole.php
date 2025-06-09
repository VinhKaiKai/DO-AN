<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NguoiDung;

class CheckRole
{
 
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kiểm tra session đăng nhập
        if ($request->session()->has('nguoi_dung_id')) {
            $userRole = $request->session()->get('vai_tro');

            if (in_array($userRole, $roles)) {
                return $next($request);
            }
        }
        return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập vào trang này.');
    }
}