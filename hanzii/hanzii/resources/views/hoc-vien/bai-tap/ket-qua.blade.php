@extends('layouts.dashboard')

@section('title', 'Kết quả bài tập')
@section('page-heading', 'Kết quả bài tập')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-clipboard-check mr-2 text-indigo-600"></i>Kết quả bài tập: {{ $baiTapDaNop->baiTap->tieu_de }}
            </h3>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Ngày nộp: {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin kết quả</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Điểm số</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if ($baiTapDaNop->diem !== null)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $baiTapDaNop->diem }} / {{ $baiTapDaNop->baiTap->diem_toi_da }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Chưa có điểm
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if ($baiTapDaNop->trang_thai == 'da_nop')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Đã nộp - Chờ chấm
                                    </span>
                                @elseif ($baiTapDaNop->trang_thai == 'da_cham')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Đã chấm
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @if ($baiTapDaNop->phan_hoi)
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Phản hồi của giáo viên</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 prose prose-sm max-w-none bg-gray-50 p-4 rounded-md">
                                {!! $baiTapDaNop->phan_hoi !!}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
          
            @if ($baiTapDaNop->baiTap->loai == 'tu_luan')
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Nội dung bài làm</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="prose prose-sm max-w-none bg-gray-50 p-4 rounded-md">
                            {!! $baiTapDaNop->noi_dung !!}
                        </div>
                    </div>
                </div>
            @elseif ($baiTapDaNop->baiTap->loai == 'file')
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">File đã nộp</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <a href="{{ asset('storage/' . $baiTapDaNop->file_path) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Tải xuống {{ $baiTapDaNop->ten_file }}
                        </a>
                    </div>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('hoc-vien.bai-tap.show', $baiTapDaNop->baiTap->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Quay lại bài tập
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 