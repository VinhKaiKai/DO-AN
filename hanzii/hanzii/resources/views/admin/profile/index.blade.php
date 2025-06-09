@extends('layouts.dashboard')

@section('title', 'Thông tin cá nhân')
@section('page-heading', 'Thông tin cá nhân')

@php
    $active = 'nguoi-dung';
    $role = 'admin';
@endphp

@section('content')
<div class="w-full bg-white rounded-lg shadow-md p-6">
    <div class="flex flex-col md:flex-row">
        <div class="md:w-1/3 mb-6 md:mb-0 flex flex-col items-center">
            <div class="w-48 h-48 bg-gray-200 rounded-full overflow-hidden mb-4">
                @if ($nguoiDung->anh_dai_dien)
                    <img src="{{ asset('storage/' . $nguoiDung->anh_dai_dien) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-4xl font-bold">
                        {{ substr($nguoiDung->ho, 0, 1) }}{{ substr($nguoiDung->ten, 0, 1) }}
                    </div>
                @endif
            </div>
            <h3 class="text-xl font-semibold">{{ $nguoiDung->ho }} {{ $nguoiDung->ten }}</h3>
            <p class="text-gray-600 mb-4">Admin</p>
            <a href="{{ route('admin.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin
            </a>
            <a href="{{ route('admin.profile.change-password') }}" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                <i class="fas fa-key mr-2"></i>Đổi mật khẩu
            </a>
        </div>
        
        <div class="md:w-2/3 md:pl-8">
            <div class="border-b pb-4 mb-4">
                <h4 class="text-lg font-semibold mb-2">Thông tin cá nhân</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Họ và tên</p>
                        <p class="font-medium">{{ $nguoiDung->ho }} {{ $nguoiDung->ten }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-medium">{{ $nguoiDung->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Số điện thoại</p>
                        <p class="font-medium">{{ $nguoiDung->so_dien_thoai ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Ngày tham gia</p>
                        <p class="font-medium">{{ $nguoiDung->created_at ? $nguoiDung->created_at->format('d/m/Y') : 'Không có thông tin' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-2">Hoạt động gần đây</h4>
                <div class="space-y-3">
                    @if(isset($recent_activities) && count($recent_activities) > 0)
                        @foreach($recent_activities as $activity)
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-sm">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 italic">Không có hoạt động gần đây</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 