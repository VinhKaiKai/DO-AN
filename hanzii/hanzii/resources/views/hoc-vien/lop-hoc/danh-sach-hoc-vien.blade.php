@extends('layouts.dashboard')

@section('title', 'Danh sách học viên lớp ' . $lopHoc->ten)
@section('page-heading', 'Danh sách học viên lớp ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách học viên lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('hoc-vien.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông tin lớp học -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin lớp học</h3>
                <p class="mt-1 text-sm text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</p>
                <p class="mt-1 text-sm text-gray-900">Hình thức: {{ $lopHoc->hinh_thuc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin giảng dạy</h3>
                <p class="mt-1 text-sm text-gray-900">Giáo viên: {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten }}</p>
                <p class="mt-1 text-sm text-gray-900">Trợ giảng: {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? $lopHoc->troGiang->nguoiDung->ho . ' ' . $lopHoc->troGiang->nguoiDung->ten ?? 'Chưa phân công' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thời gian học</h3>
                <p class="mt-1 text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                <p class="mt-1 text-sm text-gray-900">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thống kê học viên -->
    <div class="bg-white shadow rounded-lg p-5 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-red-100 flex items-center justify-center text-red-600">
                <i class="fas fa-users"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-900">Tổng số học viên</h3>
                <p class="text-xl font-semibold text-gray-800">{{ $tongSoHocVien }}</p>
            </div>
        </div>
    </div>
    
    <!-- Danh sách học viên -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Danh sách học viên</h2>
        </div>
        
        @if($danhSachHocVien->isEmpty())
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có học viên nào</h3>
                <p class="mt-1 text-sm text-gray-500">Chưa có học viên nào tham gia lớp học này.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ và tên</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tham gia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $index = 0; @endphp
                        @foreach($danhSachHocVien as $dangKy)
                            @php $index++; @endphp
                            <tr class="{{ $dangKy->hocVien->id == $hocVien->id ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                            {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ho, 0, 1) . substr($dangKy->hocVien->nguoiDung->ten, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dangKy->hocVien->nguoiDung->ho }} {{ $dangKy->hocVien->nguoiDung->ten }}
                                                @if($dangKy->hocVien->id == $hocVien->id)
                                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Bạn</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->so_dien_thoai }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection 