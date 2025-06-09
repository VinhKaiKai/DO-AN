@extends('layouts.dashboard')

@section('title', 'Chọn lớp học')
@section('page-heading', 'Chọn lớp học để quản lý bài tập')

@php
    $active = 'bai-tap';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('giao-vien.dashboard') }}" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Thành công!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Lỗi!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-tasks mr-2"></i> Chọn lớp học để quản lý bài tập
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Danh sách các lớp học mà bạn đang giảng dạy
                </p>
            </div>
        </div>

        <!-- Danh sách lớp học -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lopHocs as $lopHoc)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $lopHoc->ten }}</h3>
                                <p class="text-sm text-gray-600">{{ $lopHoc->khoaHoc->ten }}</p>
                            </div>
                            @php
                                $statusClass = '';
                                $statusText = '';
                                
                                if ($lopHoc->ngay_bat_dau > now()) {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Sắp khai giảng';
                                } elseif ($lopHoc->ngay_ket_thuc > now()) {
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Đang diễn ra';
                                } else {
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = 'Đã kết thúc';
                                }
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500">Số buổi học</p>
                                <p class="text-sm font-medium">{{ $lopHoc->so_buoi }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Số học viên</p>
                                <p class="text-sm font-medium">{{ $lopHoc->soHocVien ?? 0 }} / {{ $lopHoc->so_luong_toi_da }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Ngày bắt đầu</p>
                                <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Hình thức</p>
                                <p class="text-sm font-medium">{{ $lopHoc->hinh_thuc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <a href="{{ route('giao-vien.bai-tap.index', ['lop_hoc_id' => $lopHoc->id]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Quản lý bài tập
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="text-sm text-gray-500">Mã lớp: <span class="font-medium text-gray-900">{{ $lopHoc->ma_lop }}</span></div>
                            </div>
                            <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Chi tiết lớp <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded-lg p-8 text-center col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Bạn chưa được phân công giảng dạy lớp học nào</h3>
                    <p class="text-gray-600">Liên hệ với quản trị viên để biết thêm thông tin.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection 