@extends('layouts.dashboard')

@section('title', 'Đổi mật khẩu')
@section('page-heading', 'Đổi mật khẩu')

@php
    $active = 'profile';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Đổi mật khẩu</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Cập nhật mật khẩu mới cho tài khoản của bạn.</p>
    </div>
    
    <div class="border-t border-gray-200">
        <form action="{{ route('tro-giang.profile.update-password') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="password" name="current_password" id="current_password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Mật khẩu phải có ít nhất 8 ký tự.</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('tro-giang.profile.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Đổi mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 