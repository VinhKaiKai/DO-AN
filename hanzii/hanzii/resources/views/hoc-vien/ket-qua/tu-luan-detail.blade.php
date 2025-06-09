@extends('layouts.dashboard')

@section('title', 'Chi tiết bài tập tự luận')
@section('page-heading', 'Chi tiết bài tập tự luận')

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
                    @else
                        bg-blue-100 text-blue-800
                    @endif
                ">
                    {{ $baiTapDaNop->trang_thai == 'da_cham' ? 'Đã chấm' : 'Đã nộp' }}
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
                            <p class="text-gray-800">Tự luận</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Điểm tối đa:</span>
                            <p class="text-gray-800">{{ $baiTapDaNop->baiTap->diem_toi_da ?? 10 }}</p>
                        </div>
                        @if(isset($baiTapDaNop->baiTap->han_nop))
                        <div>
                            <span class="text-sm font-medium text-gray-500">Hạn nộp:</span>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($baiTapDaNop->baiTap->han_nop)->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if(isset($baiTapDaNop->baiTap->noi_dung) && !empty($baiTapDaNop->baiTap->noi_dung))
                <div class="mt-4">
                    <h5 class="text-base font-medium text-gray-800 mb-2">Yêu cầu bài tập:</h5>
                    <div class="bg-gray-50 p-4 rounded-lg prose prose-sm max-w-none">
                        {!! $baiTapDaNop->baiTap->noi_dung !!}
                    </div>
                </div>
                @endif
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
                                {{ $baiTapDaNop->trang_thai == 'da_cham' ? 'Đã chấm' : 'Đã nộp' }}
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
            <h4 class="text-lg font-medium text-gray-800 mb-4">Nội dung bài làm</h4>
            
            @if(isset($baiTapDaNop->noi_dung) && !empty($baiTapDaNop->noi_dung))
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="prose prose-sm max-w-none">
                        {!! $baiTapDaNop->noi_dung !!}
                    </div>
                </div>
            @else
                <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-600">
                    <p>Không tìm thấy nội dung bài làm.</p>
                </div>
            @endif
            
            @if($baiTapDaNop->file_path)
                <div class="mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-2">Tệp đính kèm</h4>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h5 class="font-medium text-gray-800">{{ $baiTapDaNop->ten_file ?? 'Tệp đính kèm' }}</h5>
                                @if($baiTapDaNop->file_size)
                                    <span class="text-sm text-gray-500">Kích thước: {{ \App\Helpers\FileHelper::formatFileSize($baiTapDaNop->file_size) }}</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('hoc-vien.ket-qua.download', $baiTapDaNop->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Tải xuống
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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