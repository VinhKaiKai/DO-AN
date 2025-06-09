@extends('layouts.dashboard')

@section('title', 'Chi tiết lớp học: ' . $lopHoc->ten)
@section('page-heading', 'Chi tiết lớp học: ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<!-- Header với các nút điều hướng -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Chi tiết lớp {{ $lopHoc->ten }}</h2>
            <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('tro-giang.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Danh sách lớp
            </a>
        </div>
    </div>
</div>

<!-- Thông tin lớp học dạng card -->
<div class="bg-white shadow rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-800">Thông tin lớp học</h2>
        <div>
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                @if($lopHoc->trang_thai == 'dang_dien_ra') bg-green-100 text-green-800
                @elseif($lopHoc->trang_thai == 'sap_khai_giang') bg-yellow-100 text-yellow-800
                @elseif($lopHoc->trang_thai == 'da_ket_thuc') bg-gray-100 text-gray-800
                @else bg-blue-100 text-blue-800
                @endif">
                @if($lopHoc->trang_thai == 'dang_dien_ra')
                    Đang diễn ra
                @elseif($lopHoc->trang_thai == 'sap_khai_giang')
                    Sắp khai giảng
                @elseif($lopHoc->trang_thai == 'da_ket_thuc')
                    Đã kết thúc
                @else
                    {{ ucfirst(str_replace('_', ' ', $lopHoc->trang_thai)) }}
                @endif
            </span>
        </div>
    </div>
    
    <div class="divide-y divide-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
            <div class="p-6">
                <h3 class="text-sm font-medium text-gray-500">Thông tin cơ bản</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Tên lớp</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</div>
                    </div>
                    @if($lopHoc->ma_lop)
                    <div>
                        <div class="text-xs text-gray-500">Mã lớp</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ma_lop }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-xs text-gray-500">Thuộc khóa học</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->khoaHoc->ten }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Giáo viên phụ trách</div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $lopHoc->giaoVien->nguoiDung->ho }} {{ $lopHoc->giaoVien->nguoiDung->ten }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-sm font-medium text-gray-500">Thời gian</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Ngày bắt đầu</div>
                        <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</div>
                    </div>
                    @if($lopHoc->ngay_ket_thuc)
                    <div>
                        <div class="text-xs text-gray-500">Ngày kết thúc</div>
                        <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-xs text-gray-500">Lịch học</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->lich_hoc ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Hình thức học</div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $lopHoc->hinh_thuc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}
                        </div>
                    </div>
                    @if($lopHoc->hinh_thuc == 'online' && $lopHoc->link_meeting)
                    <div>
                        <div class="text-xs text-gray-500">Link học trực tuyến</div>
                        <a href="{{ $lopHoc->link_meeting }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Tham gia buổi học
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-sm font-medium text-gray-500">Tổng quan</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Tổng số học viên</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->dang_ky_hocs_count ?? count($hocViens) ?? 0 }} học viên</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Tổng số bài học</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->bai_hoc_lops_count ?? 0 }} bài học</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Tổng số bài tập</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->bai_taps_count ?? 0 }} bài tập</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Tiến độ hoàn thành</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1 mt-1">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $hoanThanhTyLe ?? 0 }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500">{{ $hoanThanhTyLe ?? 0 }}% hoàn thành</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Công cụ hỗ trợ lớp học -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-12 w-12 rounded-md bg-purple-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Bình luận</h3>
                <p class="text-sm text-gray-500">Hỗ trợ học viên</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('tro-giang.binh-luan.index', ['lop_hoc_id' => $lopHoc->id]) }}" class="w-full block bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md text-center transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                Xem bình luận
            </a>
        </div>
    </div>
</div>

<!-- Danh sách bài tập đã nộp gần đây cần hỗ trợ -->
<div class="bg-white shadow rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">Bài tập đã nộp gần đây</h3>
        <a href="{{ route('tro-giang.lop-hoc.danh-sach-bai-tap', $lopHoc->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
            Xem tất cả <i class="fas fa-chevron-right ml-1"></i>
        </a>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($baiTapGanDay as $baiTapDaNop)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="text-center mr-4">
                            <div class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $baiTapDaNop->baiTap->tieu_de ?? $baiTapDaNop->baiTap->ten ?? 'Bài tập' }}</h4>
                            <p class="text-xs text-gray-500">Học viên: {{ $baiTapDaNop->hocVien->nguoiDung->ho }} {{ $baiTapDaNop->hocVien->nguoiDung->ten }}</p>
                            <p class="text-xs text-gray-500">Nộp lúc: {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Chờ chấm
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không có bài tập mới</h3>
                <p class="mt-1 text-sm text-gray-500">Không có bài tập nào được nộp gần đây.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection