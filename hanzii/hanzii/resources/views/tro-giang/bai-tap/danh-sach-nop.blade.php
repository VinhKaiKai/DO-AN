@extends('layouts.dashboard')

@section('title', 'Danh sách bài nộp')
@section('page-heading', 'Danh sách bài nộp')

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('tro-giang.lop-hoc.danh-sach-bai-tap', $lopHoc->id) }}" class="text-red-600 hover:text-red-800 mr-2">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách bài tập
        </a>
    </div>

    <!-- Thông tin bài tập -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $baiTap->tieu_de }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Bài học: {{ $baiTap->baiHoc->tieu_de }}
                    </p>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Lớp: {{ $lopHoc->ten }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">
                        <strong>Hạn nộp:</strong> {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <strong>Điểm tối đa:</strong> {{ $baiTap->diem_toi_da }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <strong>Loại bài tập:</strong> 
                        @if($baiTap->loai == 'tu_luan') Tự luận
                        @elseif($baiTap->loai == 'file') File
                        @else {{ $baiTap->loai }} @endif
                    </p>
                </div>
            </div>
            @if($baiTap->mo_ta)
            <div class="mt-3 text-sm text-gray-600 border-t pt-3">
                <p class="mb-1"><strong>Mô tả:</strong></p>
                <p>{{ $baiTap->mo_ta }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Danh sách bài nộp -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Danh sách học viên đã nộp bài
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Tổng số: {{ $baiTapDaNops->count() }} bài nộp
            </p>
        </div>
        
        @if($baiTapDaNops->isEmpty())
            <div class="px-4 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có học viên nào nộp bài</h3>
                <p class="mt-1 text-sm text-gray-500">Chưa có bài tập nào được nộp cho bài tập này.</p>
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
                                Ngày nộp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Điểm
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($baiTapDaNops as $baiNop)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            @if($baiNop->hocVien->nguoiDung->avatar)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $baiNop->hocVien->nguoiDung->avatar) }}" alt="{{ $baiNop->hocVien->nguoiDung->ho . ' ' . $baiNop->hocVien->nguoiDung->ten }}">
                                            @else
                                                <span class="text-gray-700 font-medium">
                                                    {{ strtoupper(substr($baiNop->hocVien->nguoiDung->ho, 0, 1)) . strtoupper(substr($baiNop->hocVien->nguoiDung->ten, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $baiNop->hocVien->nguoiDung->ho . ' ' . $baiNop->hocVien->nguoiDung->ten }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $baiNop->hocVien->nguoiDung->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($baiNop->ngay_nop)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if(\Carbon\Carbon::parse($baiNop->ngay_nop) > \Carbon\Carbon::parse($baiTap->han_nop))
                                            <span class="text-red-500">Nộp trễ</span>
                                        @else
                                            <span class="text-green-500">Đúng hạn</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($baiNop->trang_thai == 'da_nop') bg-yellow-100 text-yellow-800
                                        @elseif($baiNop->trang_thai == 'da_cham') bg-green-100 text-green-800
                                        @elseif($baiNop->trang_thai == 'can_nop_lai') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($baiNop->trang_thai == 'da_nop') Chờ chấm
                                        @elseif($baiNop->trang_thai == 'da_cham') Đã chấm
                                        @elseif($baiNop->trang_thai == 'can_nop_lai') Cần nộp lại
                                        @else {{ $baiNop->trang_thai }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($baiNop->trang_thai == 'da_cham')
                                        <span class="font-medium">{{ $baiNop->diem }}</span>/{{ $baiTap->diem_toi_da }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tro-giang.bai-tap.chi-tiet-nop', $baiNop->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Xem chi tiết
                                    </a>
                                    @if($baiNop->file_dinh_kem)
                                    <a href="{{ route('tro-giang.bai-tap.download', $baiNop->id) }}" class="text-red-600 hover:text-red-900" target="_blank">
                                        <i class="fas fa-download mr-1"></i> Tải file
                                    </a>
                                    @endif
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