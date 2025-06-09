@extends('layouts.dashboard')

@section('title', 'Quản lý thanh toán học phí')
@section('page-heading', 'Quản lý thanh toán học phí')

@php
    $active = 'thanh-toan-hoc-phi';
    $role = 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Form tìm kiếm -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <h5 class="text-lg font-medium text-gray-900 mb-4">Tìm kiếm</h5>
        <form action="{{ route('admin.thanh-toan-hoc-phi.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Tên khóa học</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-red-500 focus:border-red-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Tên lớp học, mã lớp...">
                    </div>
                </div>
                
                <div>
                    <label for="trang_thai_thanh_toan" class="block text-sm font-medium text-gray-700">Trạng thái thanh toán</label>
                    <select name="trang_thai_thanh_toan" id="trang_thai_thanh_toan" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="">Tất cả</option>
                        <option value="da_thanh_toan" {{ request('trang_thai_thanh_toan') == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán đủ</option>
                        <option value="thanh_toan_mot_phan" {{ request('trang_thai_thanh_toan') == 'thanh_toan_mot_phan' ? 'selected' : '' }}>Thanh toán một phần</option>
                        <option value="chua_thanh_toan" {{ request('trang_thai_thanh_toan') == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                    </select>
                </div>
                
                <div class="flex space-x-2 col-span-1 lg:justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-search mr-2"></i> Lọc
                    </button>
                    <a href="{{ route('admin.thanh-toan-hoc-phi.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-sync mr-2"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($lopHocs as $lopHoc)
        <div class="lop-hoc-card bg-white shadow-md rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg border border-gray-200">
            <div class="p-4">
                <h5 class="text-lg font-medium text-gray-900">
                    {{ $lopHoc->ten }}
                    @if($lopHoc->da_thanh_toan_day_du)
                        <span class="inline-flex items-center ml-2">
                            <i class="fas fa-check-circle text-green-500" title="Đã hoàn thiện đủ học phí"></i>
                        </span>
                    @elseif($lopHoc->da_thanh_toan_mot_phan)
                        <span class="inline-flex items-center ml-2">
                            <i class="fas fa-dot-circle text-yellow-500" title="Đã thanh toán một phần"></i>
                        </span>
                    @else
                        <span class="inline-flex items-center ml-2">
                            <i class="fas fa-times-circle text-red-500" title="Chưa thanh toán"></i>
                        </span>
                    @endif
                </h5>
                <p class="text-sm text-gray-500">{{ $lopHoc->ma_lop }}</p>
                
                <div class="flex justify-between items-center mt-4">
                    <div>
                        <p class="text-sm">
                            <i class="fas fa-book text-blue-500"></i> 
                            <span>Số bài học: {{ $lopHoc->baiHocLops->count() }} bài</span>
                        </p>
                    </div>
                    <div>
                        @if($lopHoc->da_thanh_toan_day_du)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Đã thanh toán đủ
                            </span>
                        @elseif($lopHoc->da_thanh_toan_mot_phan)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Thanh toán một phần
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Chưa thanh toán
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between">
                    <a href="{{ route('admin.thanh-toan-hoc-phi.show', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        <i class="fas fa-eye mr-2"></i> Chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<!-- Có thể thêm JavaScript bổ sung nếu cần thiết -->
@endsection 