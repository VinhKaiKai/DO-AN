@extends('layouts.dashboard')

@section('title', 'Chi tiết trợ giảng')
@section('page-heading', 'Chi tiết trợ giảng')

@php
    $active = 'tro-giang';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Thông tin trợ giảng: {{ $troGiang->nguoiDung->ho_ten }}</h2>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.tro-giang.edit', $troGiang->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 disabled:opacity-25 transition">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.tro-giang.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin cá nhân -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Thông tin cá nhân</h3>
            </div>
            <div class="p-6">
                <div class="flex justify-center mb-6">
                    <div class="w-32 h-32 rounded-full bg-purple-100 flex items-center justify-center text-4xl text-purple-700">
                        {{ strtoupper(substr($troGiang->nguoiDung->ho_ten, 0, 1)) }}
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Họ và tên</h4>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $troGiang->nguoiDung->ho_ten }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Email</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $troGiang->nguoiDung->email }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Số điện thoại</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $troGiang->nguoiDung->so_dien_thoai }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Địa chỉ</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $troGiang->nguoiDung->dia_chi ?: 'Chưa cập nhật' }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Ngày tham gia</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $troGiang->nguoiDung->tao_luc ? date('d/m/Y', strtotime($troGiang->nguoiDung->tao_luc)) : 'Không xác định' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chuyên môn -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Thông tin chuyên môn</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Bằng cấp</h4>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($troGiang->bang_cap == 'dai_hoc')
                                Đại học
                            @elseif($troGiang->bang_cap == 'thac_si')
                                Thạc sĩ
                            @elseif($troGiang->bang_cap == 'tien_si')
                                Tiến sĩ
                            @elseif($troGiang->bang_cap == 'khac')
                                Khác
                            @else
                                {{ $troGiang->bang_cap }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Số năm kinh nghiệm</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $troGiang->so_nam_kinh_nghiem }} năm</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Chuyên môn</h4>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach(explode(',', $troGiang->chuyen_mon ?? '') as $chuyenMon)
                                @if($chuyenMon)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ strtoupper($chuyenMon) }}
                                    </span>
                                @endif
                            @endforeach
                            @if(!$troGiang->chuyen_mon)
                                <span class="text-gray-500">Chưa cập nhật</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê phụ trách lớp -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Thống kê lớp học</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-700">Số lớp đang phụ trách</h4>
                        <p class="mt-1 text-2xl font-semibold text-green-800">{{ $troGiang->lopHocs->whereIn('trang_thai', ['dang_dien_ra', 'sap_khai_giang', 'sap_khai_giang'])->count() }}</p>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-700">Tổng số lớp đã phụ trách</h4>
                        <p class="mt-1 text-2xl font-semibold text-blue-800">{{ $troGiang->lopHocs->count() }}</p>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-purple-700">Tổng số học viên đã hỗ trợ</h4>
                        <p class="mt-1 text-2xl font-semibold text-purple-800">
                            {{ $troGiang->lopHocs->sum(function($lop) {
                                return $lop->dangKyHocs ? $lop->dangKyHocs->count() : 0;
                            }) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách các lớp học đang và đã phụ trách -->
    <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách lớp học phụ trách</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên lớp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khóa học</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số học viên</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($troGiang->lopHocs as $lopHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lopHoc->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</div>
                                <div class="text-sm text-gray-500">Mã lớp: {{ $lopHoc->ma_lop }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lopHoc->khoaHoc->ten ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ date('d/m/Y', strtotime($lopHoc->ngay_bat_dau)) }}</div>
                                <div class="text-sm text-gray-500">đến {{ date('d/m/Y', strtotime($lopHoc->ngay_ket_thuc)) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lopHoc->dangKyHocs ? $lopHoc->dangKyHocs->count() : 0 }}/{{ $lopHoc->so_luong_toi_da }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lopHoc->trang_thai == 'sap_khai_giang' || $lopHoc->trang_thai == 'sap_khai_giang')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Sắp khai giảng
                                    </span>
                                @elseif($lopHoc->trang_thai == 'dang_dien_ra' || $lopHoc->trang_thai == 'dang_hoat_dong')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đang diễn ra
                                    </span>
                                @elseif($lopHoc->trang_thai == 'da_ket_thuc')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Đã kết thúc
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $lopHoc->trang_thai }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Trợ giảng chưa phụ trách lớp học nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection 