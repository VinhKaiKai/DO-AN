@extends('layouts.dashboard')

@section('title', 'Chi tiết bài nộp')
@section('page-heading', 'Chi tiết bài nộp')

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('tro-giang.bai-tap.xem-bai-nop', $baiTap->id) }}" class="text-red-600 hover:text-red-800 mr-2">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
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
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin học viên và bài nộp -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin học viên -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Thông tin học viên
                </h3>
            </div>
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                        @if($baiTapDaNop->hocVien->nguoiDung->avatar)
                            <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . $baiTapDaNop->hocVien->nguoiDung->avatar) }}" alt="{{ $baiTapDaNop->hocVien->nguoiDung->ho . ' ' . $baiTapDaNop->hocVien->nguoiDung->ten }}">
                        @else
                            <span class="text-gray-700 text-lg font-medium">
                                {{ strtoupper(substr($baiTapDaNop->hocVien->nguoiDung->ho, 0, 1)) . strtoupper(substr($baiTapDaNop->hocVien->nguoiDung->ten, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">
                            {{ $baiTapDaNop->hocVien->nguoiDung->ho . ' ' . $baiTapDaNop->hocVien->nguoiDung->ten }}
                        </h4>
                        <p class="text-sm text-gray-500">
                            {{ $baiTapDaNop->hocVien->nguoiDung->email }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            SĐT: {{ $baiTapDaNop->hocVien->nguoiDung->so_dien_thoai ?? 'Chưa cập nhật' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin bài nộp -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg md:col-span-2">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Thông tin bài nộp
                </h3>
            </div>
            <div class="px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Ngày nộp</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i') }}
                            @if(\Carbon\Carbon::parse($baiTapDaNop->ngay_nop) > \Carbon\Carbon::parse($baiTap->han_nop))
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Nộp trễ
                                </span>
                            @else
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Đúng hạn
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($baiTapDaNop->trang_thai == 'da_nop') bg-yellow-100 text-yellow-800
                                @elseif($baiTapDaNop->trang_thai == 'da_cham') bg-green-100 text-green-800
                                @elseif($baiTapDaNop->trang_thai == 'can_nop_lai') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($baiTapDaNop->trang_thai == 'da_nop') Chờ chấm
                                @elseif($baiTapDaNop->trang_thai == 'da_cham') Đã chấm
                                @elseif($baiTapDaNop->trang_thai == 'can_nop_lai') Cần nộp lại
                                @else {{ $baiTapDaNop->trang_thai }} @endif
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Điểm số</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($baiTapDaNop->trang_thai == 'da_cham')
                                <span class="font-medium">{{ $baiTapDaNop->diem }}</span>/{{ $baiTap->diem_toi_da }}
                            @else
                                <span class="text-gray-400">Chưa chấm điểm</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tệp đính kèm</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($baiTapDaNop->file_dinh_kem)
                                <a href="{{ route('tro-giang.bai-tap.download', $baiTapDaNop->id) }}" class="text-red-600 hover:text-red-900" target="_blank">
                                    <i class="fas fa-file-download mr-1"></i> {{ $baiTapDaNop->ten_file }}
                                </a>
                            @else
                                <span class="text-gray-400">Không có</span>
                            @endif
                        </dd>
                    </div>
                    @if($baiTapDaNop->trang_thai == 'da_cham')
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Người chấm điểm</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $baiTapDaNop->nguoiCham ? $baiTapDaNop->nguoiCham->ho . ' ' . $baiTapDaNop->nguoiCham->ten : 'Chưa xác định' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Ngày chấm</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $baiTapDaNop->ngay_cham ? \Carbon\Carbon::parse($baiTapDaNop->ngay_cham)->format('d/m/Y H:i') : 'Chưa xác định' }}
                            </dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Nội dung bài nộp</dt>
                        <dd class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                            {!! nl2br(e($baiTapDaNop->noi_dung)) !!}
                        </dd>
                    </div>
                    @if($baiTapDaNop->trang_thai == 'da_cham' && $baiTapDaNop->nhan_xet)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Nhận xét của giáo viên</dt>
                            <dd class="mt-1 text-sm text-gray-900 p-3 bg-blue-50 rounded-md">
                                {!! nl2br(e($baiTapDaNop->nhan_xet)) !!}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection 