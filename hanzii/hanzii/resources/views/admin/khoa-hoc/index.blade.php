@extends('layouts.dashboard')

@section('title', 'Quản lý khóa học')
@section('page-heading', 'Danh sách khóa học')

@php
    $active = 'khoa-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Quản lý khóa học</h2>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.khoa-hoc.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm khóa học mới
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('admin.khoa-hoc.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Tên khóa học..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
        
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.khoa-hoc.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách khóa học -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($khoaHocs as $khoaHoc)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="relative">
                    @if($khoaHoc->hinh_anh)
                        <img src="{{ Storage::url($khoaHoc->hinh_anh) }}" alt="{{ $khoaHoc->ten }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-book text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                
                </div>
                
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $khoaHoc->ten }}</h3>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $khoaHoc->mo_ta }}</p>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-money-bill-wave text-gray-400 mr-2"></i>
                        <span>{{ number_format($khoaHoc->hoc_phi, 0, ',', '.') }} đ</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="flex flex-col items-center bg-gray-50 p-2 rounded">
                            <span class="text-xs text-gray-500">Số bài học</span>
                            <span class="font-medium">{{ $khoaHoc->tong_so_bai }} bài</span>
                        </div>
                        <div class="flex flex-col items-center bg-gray-50 p-2 rounded">
                            <span class="text-xs text-gray-500">Lớp đang mở</span>
                            <span class="font-medium">{{ $khoaHoc->lopHocs->whereIn('trang_thai', ['dang_dien_ra', 'dang_hoc'])->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.khoa-hoc.show', $khoaHoc->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 text-center text-sm font-medium rounded">
                            <i class="fas fa-eye mr-1"></i> Chi tiết
                        </a>
                        <a href="{{ route('admin.khoa-hoc.edit', $khoaHoc->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 text-center text-sm font-medium rounded">
                            <i class="fas fa-edit mr-1"></i> Sửa
                        </a>
                        <form action="{{ route('admin.lop-hoc.create') }}" method="GET" class="flex-1">
                            <input type="hidden" name="khoa_hoc_id" value="{{ $khoaHoc->id }}">
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 text-sm font-medium rounded">
                                <i class="fas fa-plus mr-1"></i> Mở lớp
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i> Cập nhật: {{ \Carbon\Carbon::parse($khoaHoc->cap_nhat_luc)->format('d/m/Y') }}
                        </span>
                        <form action="{{ route('admin.khoa-hoc.destroy', $khoaHoc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white shadow rounded-lg p-8 text-center">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có khóa học nào</h3>
                <p class="text-gray-600 mb-6">Bạn chưa tạo khóa học nào. Hãy thêm khóa học mới để bắt đầu.</p>
                <a href="{{ route('admin.khoa-hoc.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm khóa học mới
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Phân trang -->
    <div class="mt-6">
        {{ $khoaHocs->appends(request()->query())->links() }}
    </div>
    
@endsection 