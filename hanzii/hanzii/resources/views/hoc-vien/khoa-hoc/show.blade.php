@extends('layouts.dashboard')

@section('title', 'Chi tiết khóa học')
@section('page-heading', 'Chi tiết khóa học')

@php
    $active = 'khoa-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $khoaHoc->ten }}</h1>
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="mr-2">Danh mục:</span>
                        <span class="font-semibold">{{ $khoaHoc->danhMucKhoaHoc->ten ?? 'Chưa phân loại' }}</span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="inline-block text-2xl font-bold text-red-600">
                        {{ number_format($khoaHoc->hoc_phi, 0, ',', '.') }} VNĐ
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Thông tin khóa học</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">Tổng số bài học</div>
                                <div class="text-lg font-semibold">{{ $khoaHoc->tong_so_bai }} bài</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">Thời gian hoàn thành</div>
                                <div class="text-lg font-semibold">{{ $khoaHoc->thoi_gian_hoan_thanh ?? 'N/A' }} tuần</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">Trạng thái</div>
                                <div class="text-lg font-semibold">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Đang mở đăng ký
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Mô tả khóa học</h2>
                <div class="prose max-w-none">
                    <p>{{ $khoaHoc->mo_ta }}</p>
                </div>
            </div>

            @if(count($lopHocMo) > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Các lớp đang mở đăng ký</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian học</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình thức</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lopHocMo as $lop)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lop->ma_lop }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($lop->ngay_bat_dau)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($lop->ngay_ket_thuc)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $lop->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($lop->hinh_thuc == 'online')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Trực tuyến
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Trực tiếp
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $lop->so_luong_hoc_vien }}/{{ $lop->so_luong_toi_da }} học viên
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($daDangKy)
                                                <span class="text-green-600 font-medium">Đã đăng ký</span>
                                            @else
                                                <a href="{{ route('hoc-vien.dang-ky.create', $lop->id) }}" class="text-red-600 hover:text-red-900">Đăng ký ngay</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Thông báo</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Hiện tại không có lớp nào đang mở đăng ký cho khóa học này. Vui lòng quay lại sau hoặc liên hệ với chúng tôi để được tư vấn.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($khoaHocLienQuan) > 0)
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Khóa học liên quan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($khoaHocLienQuan as $khoaHocItem)
                            <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-medium text-gray-900 mb-1">{{ $khoaHocItem->ten }}</h3>
                                <p class="text-red-600 text-sm font-semibold mb-2">{{ number_format($khoaHocItem->hoc_phi, 0, ',', '.') }} VNĐ</p>
                                <a href="{{ route('hoc-vien.khoa-hoc.show', $khoaHocItem->id) }}" class="text-sm font-medium text-red-600 hover:text-red-800">Xem chi tiết</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection 