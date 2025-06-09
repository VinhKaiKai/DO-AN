@extends('layouts.dashboard')

@section('title', 'Chi tiết học viên')
@section('page-heading', 'Chi tiết học viên')

@php
    $active = 'hoc-vien';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Thông tin học viên: {{ $hocVien->nguoiDung->ho_ten }}</h2>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.hoc-vien.edit', $hocVien->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.hoc-vien.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin cá nhân -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Thông tin cá nhân</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center mb-6">
                    <div class="h-24 w-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                        {{ substr($hocVien->nguoiDung->ho, 0, 1) }}{{ substr($hocVien->nguoiDung->ten, 0, 1) }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Họ và tên:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->nguoiDung->ho_ten }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->nguoiDung->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Số điện thoại:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->nguoiDung->so_dien_thoai }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Ngày sinh:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->ngay_sinh ? \Carbon\Carbon::parse($hocVien->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Địa chỉ:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->nguoiDung->dia_chi ?: 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Trình độ học vấn:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $hocVien->trinh_do_hoc_van ?: 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Trạng thái:</span>
                        <span class="text-sm font-medium px-2 py-1 rounded-full {{ $hocVien->trang_thai == 'hoat_dong' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $hocVien->trang_thai == 'hoat_dong' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin học tập -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Các lớp học đã tham gia</h3>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $hocVien->dangKyHocs->count() }} lớp
                    </span>
                </div>
                <div class="p-6">
                    @if($hocVien->dangKyHocs->isEmpty())
                        <div class="text-center py-6">
                            <p class="text-gray-500">Học viên chưa tham gia lớp học nào</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mã lớp
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tên lớp
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Khóa học
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ngày tham gia
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($hocVien->dangKyHocs as $dangKy)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $dangKy->lopHoc->ma_lop }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $dangKy->lopHoc->ten }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $dangKy->lopHoc->khoaHoc->ten }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($dangKy->ngay_tham_gia ?? $dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    
                                                    if ($dangKy->trang_thai == 'da_xac_nhan') {
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        $statusText = 'Đã xác nhận';
                                                    } elseif ($dangKy->trang_thai == 'cho_xac_nhan') {
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        $statusText = 'Chờ xác nhận';
                                                    } elseif ($dangKy->trang_thai == 'huy') {
                                                        $statusClass = 'bg-red-100 text-red-800';
                                                        $statusText = 'Đã hủy';
                                                    } else {
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        $statusText = 'Không xác định';
                                                    }
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.lop-hoc.show', $dangKy->lopHoc->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    Chi tiết lớp
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thống kê học tập -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Thống kê học tập</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-blue-600 mb-1">Tổng số lớp</p>
                            <p class="text-3xl font-bold text-blue-800">{{ $hocVien->dangKyHocs->count() }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-green-600 mb-1">Lớp đang học</p>
                            <p class="text-3xl font-bold text-green-800">
                                {{ $hocVien->dangKyHocs->filter(function($d) { return $d->lopHoc && $d->lopHoc->trang_thai == 'dang_dien_ra' && $d->trang_thai == 'da_xac_nhan'; })->count() }}
                            </p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-purple-600 mb-1">Hoàn thành</p>
                            <p class="text-3xl font-bold text-purple-800">
                                {{ $hocVien->dangKyHocs->filter(function($d) { return $d->lopHoc && $d->lopHoc->trang_thai == 'da_ket_thuc' && $d->trang_thai == 'da_xac_nhan'; })->count() }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-medium text-gray-900 mb-3">Thông tin khác</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Ngày tham gia:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hocVien->created_at)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Cập nhật gần nhất:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hocVien->updated_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 