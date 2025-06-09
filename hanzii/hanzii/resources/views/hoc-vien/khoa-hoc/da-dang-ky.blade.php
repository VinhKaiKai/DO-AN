@extends('layouts.dashboard')

@section('title', 'Khóa học đã đăng ký')
@section('page-heading', 'Khóa học đã đăng ký')

@php
    $active = 'khoa-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách khóa học đã đăng ký</h2>
        
        <a href="{{ route('hoc-vien.khoa-hoc.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Khám phá thêm khóa học
        </a>
    </div>

    <!-- Danh sách khóa học -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($khoaHocDaDangKy as $khoaHoc)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $khoaHoc->ten }}</h3>
                    @if(isset($khoaHoc->mo_ta_ngan))
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $khoaHoc->mo_ta_ngan }}
                        </p>
                    @endif
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $khoaHoc->so_luong_lop }} lớp đã đăng ký
                        </span>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                    <a href="{{ route('hoc-vien.lop-hoc.index', ['khoa_hoc_id' => $khoaHoc->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Xem các lớp học
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Bạn chưa đăng ký khóa học nào</h3>
                <p class="mt-1 text-sm text-gray-500">Hãy bắt đầu khám phá các khóa học phù hợp với bạn.</p>
                <div class="mt-6">
                    <a href="{{ route('hoc-vien.khoa-hoc.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Khám phá khóa học
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Phân trang -->
    <div class="mt-6">
        {{ $khoaHocDaDangKy->links() }}
    </div>
@endsection 