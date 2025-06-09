@extends('layouts.dashboard')

@section('title', 'Danh sách bài tập lớp ' . $lopHoc->ten)
@section('page-heading', 'Danh sách bài tập lớp ' . $lopHoc->ten)

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

    <!-- Danh sách bài tập -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Danh sách bài tập ({{ $baiTaps->count() }} bài tập)
            </h3>
        </div>
        
        @if($baiTaps->isEmpty())
            <div class="px-4 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài tập</h3>
                <p class="mt-1 text-sm text-gray-500">Lớp học này hiện chưa có bài tập nào được tạo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bài tập
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bài học
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Loại
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hạn nộp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Điểm tối đa
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số bài đã nộp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số bài cần chấm
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($baiTaps as $baiTap)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $baiTap->tieu_de }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $baiTap->baiHoc->tieu_de }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($baiTap->loai == 'tu_luan') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if($baiTap->loai == 'tu_luan') Tự luận
                                        @elseif($baiTap->loai == 'file') File
                                        @else {{ $baiTap->loai }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if(\Carbon\Carbon::now() > \Carbon\Carbon::parse($baiTap->han_nop))
                                            <span class="text-red-600">Đã hết hạn</span>
                                        @else
                                            <span>Còn {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($baiTap->han_nop)) }} ngày</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ $baiTap->diem_toi_da }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $baiTap->so_luong_nop }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($baiTap->so_luong_chua_cham > 0)
                                        <div class="text-sm font-medium text-red-600">{{ $baiTap->so_luong_chua_cham }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-400">0</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tro-giang.bai-tap.xem-bai-nop', $baiTap->id) }}" class="text-red-600 hover:text-red-900">
                                        Xem bài nộp
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
@endsection 