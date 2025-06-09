@extends('layouts.dashboard')

@section('title', 'Chi tiết kết quả học tập')
@section('page-heading', 'Chi tiết kết quả học tập')

@php
    $active = 'ket-qua';
    $role = 'hoc_vien';
@endphp

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-medium text-gray-900">{{ $baiTapDaNop->baiTap->tieu_de ?? 'Chi tiết bài tập' }}</h3>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($baiTapDaNop->trang_thai == 'da_cham')
                        bg-green-100 text-green-800
                    @elseif($baiTapDaNop->trang_thai == 'da_nop')
                        bg-blue-100 text-blue-800
                    @else
                        bg-gray-100 text-gray-800
                    @endif
                ">
                    {{ $baiTapDaNop->trang_thai == 'da_cham' ? 'Đã chấm' : 
                      ($baiTapDaNop->trang_thai == 'da_nop' ? 'Đã nộp' : 'Đang xử lý') }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-lg font-medium text-gray-800 mb-2">Thông tin bài tập</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tên bài tập:</span>
                            <p class="text-gray-800">{{ $baiTapDaNop->baiTap->tieu_de ?? 'Không có tiêu đề' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Bài học:</span>
                            <p class="text-gray-800">{{ $baiTapDaNop->baiTap->baiHoc->tieu_de ?? 'Không có bài học' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Lớp học:</span>
                            <p class="text-gray-800">
                                @if(isset($baiTapDaNop->baiTap->baiHoc->baiHocLops))
                                    @foreach($baiTapDaNop->baiTap->baiHoc->baiHocLops as $baiHocLop)
                                        {{ $baiHocLop->lopHoc->ten ?? 'Không xác định' }}
                                        @if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    Không xác định
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Loại bài tập:</span>
                            <p class="text-gray-800">
                                @if($baiTapDaNop->baiTap && $baiTapDaNop->baiTap->loai)
                                    @if($baiTapDaNop->baiTap->loai == 'tu_luan')
                                        Tự luận
                                    @elseif($baiTapDaNop->baiTap->loai == 'file')
                                        Nộp file
                                    @else
                                        {{ ucfirst($baiTapDaNop->baiTap->loai) }}
                                    @endif
                                @else
                                    Không xác định
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Điểm tối đa:</span>
                            <p class="text-gray-800">{{ $baiTapDaNop->baiTap->diem_toi_da ?? 10 }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Hạn nộp:</span>
                            <p class="text-gray-800">
                                @if(isset($baiTapDaNop->baiTap->han_nop))
                                    {{ \Carbon\Carbon::parse($baiTapDaNop->baiTap->han_nop)->format('d/m/Y H:i') }}
                                @else
                                    Không có hạn nộp
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-medium text-gray-800 mb-2">Kết quả của bạn</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Ngày nộp:</span>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop ?? $baiTapDaNop->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Trạng thái:</span>
                            <p class="font-medium 
                                @if($baiTapDaNop->trang_thai == 'da_cham') text-green-600
                                @elseif($baiTapDaNop->trang_thai == 'da_nop') text-blue-600
                                @else text-gray-600 @endif">
                                {{ $baiTapDaNop->trang_thai == 'da_cham' ? 'Đã chấm' : 
                                  ($baiTapDaNop->trang_thai == 'da_nop' ? 'Đã nộp - Chờ chấm' : 'Đang xử lý') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Điểm số:</span>
                            @if(isset($baiTapDaNop->diem))
                                <p class="text-2xl font-bold 
                                    {{ $baiTapDaNop->diem >= 8 ? 'text-green-600' : 
                                       ($baiTapDaNop->diem >= 6.5 ? 'text-blue-600' : 
                                        ($baiTapDaNop->diem >= 5 ? 'text-yellow-600' : 'text-red-600')) }}">
                                    {{ number_format($baiTapDaNop->diem, 1) }}/{{ $baiTapDaNop->baiTap->diem_toi_da ?? 10 }}
                                </p>
                            @else
                                <p class="text-gray-600">Chưa có điểm</p>
                            @endif
                        </div>
                        @if($baiTapDaNop->phan_hoi)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Nhận xét:</span>
                            <p class="text-gray-800 mt-1 p-2 bg-white rounded border border-gray-200">{{ $baiTapDaNop->phan_hoi }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <h4 class="text-lg font-medium text-gray-800 mb-2">Nội dung bài làm</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
                @if($baiTapDaNop->baiTap && $baiTapDaNop->baiTap->loai)
                    @if($baiTapDaNop->baiTap->loai == 'tu_luan')
                        @if($baiTapDaNop->noi_dung)
                            <div class="prose max-w-none">
                                {!! $baiTapDaNop->noi_dung !!}
                            </div>
                        @else
                            <div class="text-gray-600">Không có nội dung</div>
                        @endif
                    @elseif($baiTapDaNop->baiTap->loai == 'file')
                        @if($baiTapDaNop->file_path)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <a href="{{ asset('storage/' . $baiTapDaNop->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $baiTapDaNop->ten_file ?? 'Tải file đã nộp' }}
                                </a>
                            </div>
                        @else
                            <div class="text-gray-600">Không có file đính kèm</div>
                        @endif
                    @endif
                @else
                    <div class="text-gray-600">Không có nội dung</div>
                @endif
            </div>
        </div>
        
        <div class="mt-6 text-right">
            <a href="{{ route('hoc-vien.ket-qua.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection 