<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Kiểm tra xác thực bằng Laravel Auth
        if (Auth::check()) {
            return $next($request);
        }
        
        // Kiểm tra xác thực bằng session custom
        if ($request->session()->has('nguoi_dung_id')) {
            return $next($request);
        }

        // Chưa đăng nhập, chuyển hướng
        return $this->redirectTo($request) 
            ? redirect($this->redirectTo($request)) 
            : abort(401);
    }
}