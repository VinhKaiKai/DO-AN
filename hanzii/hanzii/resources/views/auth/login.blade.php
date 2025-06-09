@extends('layouts.app')

@section('content')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<div class="min-h-screen flex items-center justify-center"
    style="background: url('https://images.unsplash.com/photo-1547981609-4b6bfe67ca0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div
        class="w-full max-w-md sm:max-w-xs bg-white rounded-3xl shadow-2xl p-10 sm:p-4 border border-white/60 mt-32 mb-5">
        <div class="flex flex-col items-center mb-8">
            <div class="mb-4 flex justify-center items-center">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOFTWYGfZEqslu0RiuJrcbBZndoN9JjmvHg&s"
                    alt="Logo Tiếng Trung Lý Thú" class="w-48 h-40 sm:w-28 sm:h-20 object-contain">
            </div>
            <h2 class="text-3xl sm:text-xl font-extrabold text-center drop-shadow mb-2" style="color: #EE1C25;">Chào
                mừng bạn trở lại!</h2>
            <p class="text-gray-500 text-sm text-center">Đăng nhập để tiếp tục sử dụng dịch vụ của chúng tôi</p>
        </div>
        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-envelope text-lg" style="color:#EE1C25;"></i>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none rounded-lg block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition sm:text-sm bg-white shadow-sm"
                            placeholder="Nhập email..." value="{{ old('email') }}">
                    </div>
                    @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-lock text-lg" style="color:#EE1C25;"></i>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none rounded-lg block w-full pl-10 pr-10 py-2 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition sm:text-sm bg-white shadow-sm"
                            placeholder="Nhập mật khẩu...">
                        <button type="button"
                            class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-400 hover:text-blue-500 focus:outline-none">
                            <i class="fa-solid fa-eye h-5 w-5 eye-icon mt-2" style="display:inline;"></i>
                            <i class="fa-solid fa-eye-slash h-5 w-5 eye-slash-icon mt-2" style="display:none;"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-between mt-2">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}"
                        class="font-medium text-blue-600 hover:text-blue-400 transition">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" style="background: linear-gradient( #EE1C25);"
                    class="w-full flex justify-center py-3 px-4 text-lg rounded-lg font-extrabold uppercase tracking-wider text-white shadow-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-yellow-200">
                    Đăng nhập
                </button>
            </div>
            <div class="mt-4 text-center text-sm text-gray-500">
                Chưa có tài khoản?
                <a href="{{ route('register') }}"
                    class="font-medium text-blue-500 hover:text-yellow-500 transition">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const relative = btn.closest('.relative');
            const input = relative.querySelector('input');
            const eye = btn.querySelector('.eye-icon');
            const eyeSlash = btn.querySelector('.eye-slash-icon');
            if (input.type === 'password') {
                input.type = 'text';
                eye.style.display = 'none';
                eyeSlash.style.display = 'inline';
            } else {
                input.type = 'password';
                eye.style.display = 'inline';
                eyeSlash.style.display = 'none';
            }
        });
    });
});
</script>
@endsection