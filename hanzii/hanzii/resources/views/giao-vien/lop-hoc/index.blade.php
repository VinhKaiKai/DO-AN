@extends('layouts.dashboard')

@section('title', 'Danh sách lớp học giảng dạy')
@section('page-heading', 'Danh sách lớp học giảng dạy')

@php
    $active = 'lop-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Lớp học giảng dạy</h2>
           
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('giao-vien.lop-hoc.index') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <div class="flex-1">
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái lớp</label>
                <select id="trang_thai" name="trang_thai" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="sap_khai_giang" {{ request('trang_thai') == 'sap_khai_giang' ? 'selected' : '' }}>Sắp khai giảng</option>
                    <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="da_ket_thuc" {{ request('trang_thai') == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
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
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    Lọc lớp học
                </button>
                <a href="{{ route('giao-vien.lop-hoc.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    Reset
                </a>
            </div>
        </form>
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
                            
                            if ($lopHoc->trang_thai_hien_thi == 'sap_khai_giang') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'Sắp khai giảng';
                            } elseif ($lopHoc->trang_thai_hien_thi == 'dang_dien_ra') {
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
                            <p class="text-sm font-medium">{{ $lopHoc->soHocVien }} / {{ $lopHoc->so_luong_toi_da }}</p>
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
                    
                    <div class="mt-5 flex flex-col space-y-2">
                        <a href="{{ route('giao-vien.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="bg-white hover:bg-gray-50 text-gray-700 py-2 px-4 border border-gray-300 rounded-md text-center text-sm font-medium">
                            Danh sách học viên
                        </a>
                    </div>
                </div>
                
                <div class="p-5 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Tiến độ</p>
                                <p class="text-sm font-medium">{{ $lopHoc->tienDo() }}%</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('giao-vien.bai-hoc.index', ['lop_hoc_id' => $lopHoc->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Bài học <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center col-span-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bạn chưa có lớp học nào</h3>
                <p class="text-gray-600">Hiện tại bạn chưa được phân công giảng dạy lớp học nào.</p>
            </div>
        @endforelse
    </div>

    <!-- Phân trang -->
    <div class="mt-6">
        {{ $lopHocs->appends(request()->query())->links() }}
    </div>
@endsection 