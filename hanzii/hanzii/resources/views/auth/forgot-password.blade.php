@extends('layouts.app')

@section('content')
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<div class="min-h-screen flex items-center justify-center"
    style="background: url('https://images.unsplash.com/photo-1547981609-4b6bfe67ca0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="w-full max-w-md sm:max-w-xs bg-white rounded-3xl shadow-2xl p-10 sm:p-4 border border-white/60 mt-32 mb-5">
        <div class="flex flex-col items-center mb-8">
            <div class="mb-4 flex justify-center items-center">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWOFTWYGfZEqslu0RiuJrcbBZndoN9JjmvHg&s"
                    alt="Logo Tiếng Trung Lý Thú" class="w-48 h-40 sm:w-28 sm:h-20 object-contain">
            </div>
            <h2 class="text-3xl sm:text-xl font-extrabold text-center drop-shadow mb-2" style="color: #EE1C25;">Quên mật khẩu</h2>
            <p class="text-gray-500 text-sm text-center">Vui lòng nhập địa chỉ email của bạn để nhận liên kết đặt lại mật khẩu</p>
        </div>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
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

            <div class="mt-6">
                <button type="submit" style="background: linear-gradient( #EE1C25);"
                    class="w-full flex justify-center py-3 px-4 text-lg rounded-lg font-extrabold uppercase tracking-wider text-white shadow-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-yellow-200">
                    Gửi liên kết đặt lại mật khẩu
                </button>
            </div>

            <div class="mt-4 text-center text-sm text-gray-500">
                <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-yellow-500 transition">
                    Quay lại trang đăng nhập
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 