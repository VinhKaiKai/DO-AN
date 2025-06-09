@extends('layouts.dashboard')

@section('title', 'Tham gia lớp học')
@section('page-heading', 'Tham gia lớp học')

@php
    $active = 'lop-hoc';
    $role = 'hoc-vien';
@endphp

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Thông tin lớp học</h2>
            <a href="{{ route('hoc-vien.lop-hoc.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <div class="bg-yellow-50 rounded-lg p-6 mb-6 border border-yellow-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800">
                        Bạn sắp tham gia lớp học: {{ $lopHoc->ten }}
                    </h3>
                    <div class="mt-2 text-yellow-700">
                        <p>Vui lòng xác nhận thông tin lớp học trước khi tham gia. Sau khi tham gia, bạn sẽ có quyền truy cập vào tất cả bài học và tài liệu của lớp.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Thông tin lớp học</h3>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Mã lớp:</span>
                        <span class="font-medium">{{ $lopHoc->ma_lop }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Khóa học:</span>
                        <span class="font-medium">{{ $lopHoc->khoaHoc->ten }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($lopHoc->trang_thai == 'dang_dien_ra') bg-green-100 text-green-800 
                            @elseif($lopHoc->trang_thai == 'sap_khai_giang') bg-blue-100 text-blue-800 
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($lopHoc->trang_thai == 'dang_dien_ra') Đang diễn ra
                            @elseif($lopHoc->trang_thai == 'sap_khai_giang') Sắp khai giảng
                            @else Đã kết thúc @endif
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Hình thức:</span>
                        <span class="font-medium">
                            @if($lopHoc->hinh_thuc == 'online') Trực tuyến @else Tại trung tâm @endif
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Thời gian học:</span>
                        <span class="font-medium">{{ $lopHoc->lich_hoc }}</span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Thông tin giảng viên</h3>
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        {{ strtoupper(substr($lopHoc->giaoVien->nguoiDung->ho_ten, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">{{ $lopHoc->giaoVien->nguoiDung->ho_ten }}</h4>
                        <p class="text-sm text-gray-500">Giáo viên chính</p>
                    </div>
                </div>

                @if($lopHoc->troGiang)
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        {{ strtoupper(substr($lopHoc->troGiang->nguoiDung->ho_ten, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">{{ $lopHoc->troGiang->nguoiDung->ho_ten }}</h4>
                        <p class="text-sm text-gray-500">Trợ giảng</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="border border-gray-200 rounded-lg p-4 mt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Thông tin lịch học</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt đầu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết thúc</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng số buổi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lịch học</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lopHoc->so_buoi ?? $lopHoc->khoaHoc->thoi_luong }} buổi
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lopHoc->lich_hoc }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 text-center">
            <form action="{{ route('hoc-vien.lop-hoc.tham-gia', $lopHoc->id) }}" method="POST">
                @csrf
                <p class="text-gray-600 mb-4">Nhấn nút bên dưới để xác nhận tham gia lớp học</p>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-check-circle mr-2"></i> Xác nhận tham gia
                </button>
            </form>
        </div>
    </div>
@endsection 