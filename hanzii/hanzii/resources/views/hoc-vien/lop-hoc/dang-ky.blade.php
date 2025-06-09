@extends('layouts.dashboard')

@section('title', 'Đăng ký lớp học')
@section('page-heading', 'Đăng ký lớp học')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách lớp học mở đăng ký</h2>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('hoc-vien.lop-hoc.dang-ky') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <div class="flex-1">
                <label for="khoa_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Khóa học</label>
                <select id="khoa_hoc_id" name="khoa_hoc_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả khóa học</option>
                    @foreach($khoaHocs as $khoaHoc)
                        <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>
                            {{ $khoaHoc->ten }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    Lọc kết quả
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách lớp học -->
    @if(count($lopHocs) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lopHocs as $lopHoc)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $lopHoc->ten }}</h3>
                        <p class="text-sm text-gray-600">{{ $lopHoc->khoaHoc->ten }}</p>
                    </div>
                    <div class="p-4">
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Thời lượng: {{ $lopHoc->thoi_luong }} buổi</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <span>Học phí: {{ number_format($lopHoc->hoc_phi, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Giáo viên: {{ $lopHoc->giaoVien->nguoiDung->ho }} {{ $lopHoc->giaoVien->nguoiDung->ten }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Hình thức: {{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Học trực tuyến' : 'Học tại trung tâm' }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            @if(in_array($lopHoc->id, $daDangKy))
                                <button disabled class="w-full bg-gray-400 text-white py-2 px-4 rounded-md">
                                    Đã đăng ký
                                </button>
                            @else
                                <a href="{{ route('hoc-vien.lop-hoc.dang-ky-lop', $lopHoc->id) }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-md">
                                    Đăng ký ngay
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $lopHocs->links() }}
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-8 text-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có lớp học nào đang mở đăng ký</h3>
            <p class="text-gray-600">Hiện tại chưa có lớp học nào đang mở đăng ký. Vui lòng quay lại sau.</p>
        </div>
    @endif
@endsection 