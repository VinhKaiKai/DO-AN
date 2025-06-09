@extends('layouts.dashboard')

@section('title', 'Danh sách khóa học')
@section('page-heading', 'Danh sách khóa học')

@php
    $active = 'khoa-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách khóa học</h2>
        
        <div class="flex space-x-2">
            <div class="relative">
                <button id="filter-button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Lọc
                </button>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div id="filter-panel" class="bg-white shadow rounded-lg mb-6 p-4 hidden">
        <form action="{{ route('hoc-vien.khoa-hoc.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="danh_muc_id" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                <select id="danh_muc_id" name="danh_muc_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả danh mục</option>
                    @if(isset($danhMucs))
                        @foreach($danhMucs as $danhMuc)
                            <option value="{{ $danhMuc->id }}" {{ request('danh_muc_id') == $danhMuc->id ? 'selected' : '' }}>
                                {{ $danhMuc->ten }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label for="tu_khoa" class="block text-sm font-medium text-gray-700 mb-1">Từ khóa</label>
                <input type="text" id="tu_khoa" name="tu_khoa" value="{{ request('tu_khoa') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Tên khóa học...">
            </div>
            <div>
                <label for="sap_xep" class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp</label>
                <select id="sap_xep" name="sap_xep" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="moi_nhat" {{ request('sap_xep') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="gia_tang" {{ request('sap_xep') == 'gia_tang' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="gia_giam" {{ request('sap_xep') == 'gia_giam' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
            </div>
            <div class="md:col-span-2 lg:col-span-1 flex items-end">
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách khóa học -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($khoaHocs as $khoaHoc)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $khoaHoc->ten }}</h3>
                    <div class="text-sm text-gray-500 mb-3">
                        <span class="font-medium">Danh mục:</span> 
                        {{ $khoaHoc->danhMucKhoaHoc->ten ?? 'Chưa phân loại' }}
                    </div>
                    <div class="mb-3">
                        <span class="text-red-600 font-bold text-lg">{{ number_format($khoaHoc->hoc_phi, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ $khoaHoc->mo_ta ?? 'Chưa có mô tả' }}
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $khoaHoc->tong_so_bai }} bài học
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $khoaHoc->thoi_gian_hoan_thanh ?? 'N/A' }} tuần
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                    <a href="{{ route('hoc-vien.khoa-hoc.show', $khoaHoc->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Xem chi tiết
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy khóa học</h3>
                <p class="mt-1 text-sm text-gray-500">Không có khóa học nào phù hợp với điều kiện tìm kiếm.</p>
            </div>
        @endforelse
    </div>

    <!-- Phân trang -->
    <div class="mt-6">
        {{ $khoaHocs->links() }}
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterPanel = document.getElementById('filter-panel');
        
        filterButton.addEventListener('click', function() {
            filterPanel.classList.toggle('hidden');
        });
    });
</script>
@endsection 