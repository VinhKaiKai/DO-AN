@extends('layouts.dashboard')

@section('title', 'Trang Quản Trị')
@section('page-heading', 'Bảng Điều Khiển')

@php
$active = 'dashboard';
$role = 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Tổng học viên</p>
                    <p class="text-3xl font-bold mt-1">{{$tongHocVien}}</p>

                </div>
                <div class="bg-white bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Tổng lớp học</p>
                    <p class="text-3xl font-bold mt-1">{{$tongLopHoc}}</p>

                </div>
                <div class="bg-white bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Tổng nhân viên</p>
                    <p class="text-3xl font-bold mt-1">{{ $tongNhanVien }}</p>
                    <p class="text-sm mt-2">Tổng giáo viên: {{ $tongGiaoVien }} | Tổng trợ giảng: {{ $tongTroGiang }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full">
        <!-- Lớp học sắp khai giảng -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg shadow-md border border-indigo-200">
            <div class="p-4 border-b border-indigo-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-indigo-800">
                    <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                    Lớp học sắp khai giảng
                </h2>
                <a href="{{ route('admin.lop-hoc.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-indigo-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                    Mã lớp
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                    Tên lớp
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                    Khóa học
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                    Giáo viên
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                    Ngày khai giảng
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-200">
                            @forelse($danhSachLopHocSapKhaiGiang as $lopHoc)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $lopHoc->ma_lop }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-800">
                                        {{ $lopHoc->ten }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $lopHoc->khoaHoc->ten }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-sm text-gray-500 text-center">
                                    Không có lớp học nào sắp khai giảng
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('styles')
    <style>
    /* Custom styles for charts */
    .chart-container {
        height: 300px;
    }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

    </script>
    @endpush