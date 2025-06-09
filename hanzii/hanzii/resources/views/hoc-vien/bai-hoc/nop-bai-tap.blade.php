@extends('layouts.dashboard')

@section('title', 'Nộp bài tập')
@section('page-heading', $baiTap->ten ?? 'Nộp bài tập')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
    $quaHan = \Carbon\Carbon::now() > \Carbon\Carbon::parse($baiTap->han_nop);
    $coTheNop = !$quaHan || ($baiTapDaNop && $baiTapDaNop->trang_thai !== 'da_cham');
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('hoc-vien.bai-hoc.show', ['lopHocId' => $lopHocId, 'baiHocId' => $baiHocId]) }}" 
       class="inline-flex items-center text-blue-600 hover:text-blue-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Quay lại bài học
    </a>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $baiTap->ten }}</h2>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="font-medium">Hạn nộp:</span> {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                </p>
            </div>
            <div>
                @if($baiTapDaNop)
                    @if($baiTapDaNop->trang_thai == 'da_cham')
                        <span class="inline-flex items-center bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Đã chấm ({{ $baiTapDaNop->diem }}/10)
                        </span>
                    @else
                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Đã nộp
                        </span>
                    @endif
                @elseif($quaHan)
                    <span class="inline-flex items-center bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Quá hạn
                    </span>
                @else
                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Chưa nộp
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="prose max-w-none mb-6">
            <h3>Mô tả bài tập</h3>
            {!! $baiTap->mo_ta !!}
        </div>
        
        @if($baiTapDaNop && $baiTapDaNop->trang_thai == 'da_cham')
            <div class="mt-6 p-4 border rounded-lg bg-green-50">
                <h3 class="font-medium text-green-800 mb-2">Kết quả đánh giá</h3>
                <div class="flex items-center mb-3">
                    <span class="text-3xl font-bold text-green-700">{{ $baiTapDaNop->diem }}</span>
                    <span class="text-xl text-green-600 ml-1">/10</span>
                </div>
                @if($baiTapDaNop->nhan_xet)
                    <div class="mt-2">
                        <h4 class="text-sm font-medium text-green-800">Nhận xét của giáo viên:</h4>
                        <p class="text-sm text-green-700 mt-1">{{ $baiTapDaNop->nhan_xet }}</p>
                    </div>
                @endif
            </div>
        @endif
        
        @if($coTheNop)
            <form action="{{ route('hoc-vien.bai-hoc.nop-bai-tap', ['lopHocId' => $lopHocId, 'baiHocId' => $baiHocId, 'baiTapId' => $baiTap->id]) }}" 
                  method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                
                <div class="mb-4">
                    <label for="noi_dung" class="block text-sm font-medium text-gray-700 mb-1">Nội dung bài làm</label>
                    <textarea id="noi_dung" name="noi_dung" rows="8" 
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ $baiTapDaNop->noi_dung ?? old('noi_dung') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Nhập nội dung bài làm của bạn (nếu có).</p>
                </div>
                
                <div class="mb-6">
                    <label for="file_dinh_kem" class="block text-sm font-medium text-gray-700 mb-1">File đính kèm</label>
                    <input type="file" id="file_dinh_kem" name="file_dinh_kem" 
                           class="block w-full text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    <p class="mt-1 text-sm text-gray-500">
                        Tải lên file bài làm của bạn (nếu có). Định dạng hỗ trợ: PDF, Word, Excel, hình ảnh. Kích thước tối đa 10MB.
                    </p>
                    
                    @if($baiTapDaNop && $baiTapDaNop->file_dinh_kem)
                        <div class="mt-3 flex items-center p-3 bg-gray-50 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">File đã nộp trước đó: {{ $baiTapDaNop->ten_file }}</p>
                                <p class="text-xs text-gray-500">Nộp lúc: {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-500">Lưu ý: Nếu bạn tải lên file mới, file cũ sẽ bị thay thế.</p>
                    @endif
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                        @if($baiTapDaNop)
                            Cập nhật bài làm
                        @else
                            Nộp bài
                        @endif
                    </button>
                </div>
            </form>
        @else
            <div class="mt-6 p-4 border rounded-lg bg-red-50 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-red-800">Không thể nộp bài</h3>
                <p class="text-red-700 mt-1">Bài tập đã quá hạn nộp hoặc đã được giáo viên chấm điểm.</p>
            </div>
        @endif
        
        @if($baiTapDaNop)
            <div class="mt-6">
                <h3 class="font-medium text-gray-900 mb-3">Bài làm đã nộp</h3>
                
                @if($baiTapDaNop->noi_dung)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Nội dung:</h4>
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <pre class="whitespace-pre-wrap text-sm">{{ $baiTapDaNop->noi_dung }}</pre>
                        </div>
                    </div>
                @endif
                
                @if($baiTapDaNop->file_dinh_kem)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">File đính kèm:</h4>
                        <div class="flex items-center p-3 bg-gray-50 rounded-md border">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">{{ $baiTapDaNop->ten_file }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($baiTapDaNop->kich_thuoc_file / 1024, 2) }} KB</p>
                            </div>
                            <a href="{{ Storage::url($baiTapDaNop->file_dinh_kem) }}" target="_blank" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs px-3 py-1 rounded-md flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Xem
                            </a>
                        </div>
                    </div>
                @endif
                
                <div class="text-sm text-gray-600">
                    <p>Thời gian nộp: {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i') }}</p>
                    @if($baiTapDaNop->ngay_cap_nhat && $baiTapDaNop->ngay_cap_nhat != $baiTapDaNop->ngay_nop)
                        <p>Cập nhật lần cuối: {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_cap_nhat)->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 