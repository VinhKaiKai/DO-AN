@extends('layouts.dashboard')

@section('title', 'Danh sách học viên lớp ' . $lopHoc->ten)
@section('page-heading', 'Danh sách học viên lớp ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('tro-giang.lop-hoc.show', $lopHoc->id) }}" class="text-red-600 hover:text-red-800 mr-2">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin lớp học -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $lopHoc->ten }} ({{ $lopHoc->ma_lop }})
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Khóa học: {{ $lopHoc->khoaHoc->ten }}
                </p>
            </div>
            <div>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($lopHoc->trang_thai == 'dang_dien_ra') bg-green-100 text-green-800
                    @elseif($lopHoc->trang_thai == 'sap_dien_ra') bg-blue-100 text-blue-800
                    @elseif($lopHoc->trang_thai == 'da_ket_thuc') bg-gray-100 text-gray-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    @if($lopHoc->trang_thai == 'dang_dien_ra') Đang diễn ra
                    @elseif($lopHoc->trang_thai == 'sap_dien_ra') Sắp diễn ra
                    @elseif($lopHoc->trang_thai == 'da_ket_thuc') Đã kết thúc
                    @else {{ $lopHoc->trang_thai }} @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Danh sách học viên -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Danh sách học viên ({{ $hocViens->count() }} học viên)
            </h3>
        </div>
        
        @if($hocViens->isEmpty())
            <div class="px-4 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không có học viên</h3>
                <p class="mt-1 text-sm text-gray-500">Lớp học này hiện chưa có học viên nào được xác nhận tham gia.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Học viên
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tham gia
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tiến độ học tập
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($hocViens as $dangKy)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            @if($dangKy->hocVien->nguoiDung->avatar)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $dangKy->hocVien->nguoiDung->avatar) }}" alt="{{ $dangKy->hocVien->nguoiDung->ho . ' ' . $dangKy->hocVien->nguoiDung->ten }}">
                                            @else
                                                <span class="text-gray-700 font-medium">
                                                    {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ho, 0, 1)) . strtoupper(substr($dangKy->hocVien->nguoiDung->ten, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dangKy->hocVien->nguoiDung->ho . ' ' . $dangKy->hocVien->nguoiDung->ten }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                SĐT: {{ $dangKy->hocVien->nguoiDung->so_dien_thoai ?? 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $dangKy->hocVien->nguoiDung->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $tienDoHocTap[$dangKy->hoc_vien_id]['phan_tram'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $tienDoHocTap[$dangKy->hoc_vien_id]['da_hoan_thanh'] }}/{{ $tienDoHocTap[$dangKy->hoc_vien_id]['tong_bai_hoc'] }} bài học ({{ $tienDoHocTap[$dangKy->hoc_vien_id]['phan_tram'] }}%)
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($dangKy->trang_thai == 'dang_hoc' || $dangKy->trang_thai == 'da_duyet') bg-green-100 text-green-800
                                        @elseif($dangKy->trang_thai == 'cho_duyet') bg-yellow-100 text-yellow-800
                                        @elseif($dangKy->trang_thai == 'da_huy') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($dangKy->trang_thai == 'dang_hoc') Đang học
                                        @elseif($dangKy->trang_thai == 'da_duyet') Đã duyệt
                                        @elseif($dangKy->trang_thai == 'cho_duyet') Chờ duyệt
                                        @elseif($dangKy->trang_thai == 'da_huy') Đã hủy
                                        @else {{ $dangKy->trang_thai }} @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection 