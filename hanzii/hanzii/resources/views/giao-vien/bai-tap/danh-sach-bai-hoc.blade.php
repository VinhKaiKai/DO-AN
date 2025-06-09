@extends('layouts.dashboard')

@section('title', 'Danh sách bài học')
@section('page-heading', 'Danh sách bài học')

@php
    $active = 'bai-tap';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('giao-vien.bai-tap.index') }}" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách lớp học
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
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-book mr-2"></i> Danh sách bài học trong lớp "{{ $lopHoc->ten }}"
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Khóa học: {{ $lopHoc->khoaHoc->ten }} | Mã lớp: {{ $lopHoc->ma_lop }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-info-circle mr-2"></i> Chi tiết lớp học
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($baiHocs->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <ul class="divide-y divide-gray-200">
                    @foreach($baiHocs as $baiHoc)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $baiHoc->tieu_de }}
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('giao-vien.bai-tap.index', ['bai_hoc_id' => $baiHoc->id]) }}" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-tasks mr-2"></i> Quản lý bài tập
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có bài học nào</h3>
                <p class="text-gray-600 mb-4">Lớp học này chưa có bài học nào được thêm vào.</p>
                <a href="{{ route('giao-vien.bai-hoc.create') }}?lop_id={{ $lopHoc->id }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tạo bài học mới
                </a>
            </div>
        @endif
    </div>
@endsection 