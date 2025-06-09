@extends('layouts.dashboard')

@section('title', 'Chi tiết bài tập')
@section('page-heading', 'Chi tiết bài tập')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-tasks mr-2 text-indigo-600"></i>{{ $baiTap->tieu_de }}
            </h3>
            <div>
                <a href="{{ route('hoc-vien.lop-hoc.show', ['id' => $lopHoc->id]) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại lớp học
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin bài tập</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Tiêu đề</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $baiTap->tieu_de }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Lớp học</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lopHoc->ten }}</dd>
                        </div>
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Loại bài tập</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($baiTap->loai == 'tu_luan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Tự luận
                                    </span>
                                @elseif($baiTap->loai == 'file')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Nộp file
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Điểm tối đa</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $baiTap->diem_toi_da }}</dd>
                        </div>
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Hạn nộp</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($baiTap->han_nop)
                                    {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                                    @if(now() > $baiTap->han_nop)
                                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Đã hết hạn
                                        </span>
                                    @else
                                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Còn hạn
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Không giới hạn
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nội dung bài tập</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 prose prose-sm max-w-none">
                                {!! $baiTap->noi_dung !!}
                            </dd>
                        </div>
                        @if($baiTap->file_dinh_kem)
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">File đính kèm</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="{{ asset('storage/' . $baiTap->file_dinh_kem) }}" target="_blank" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Tải xuống {{ $baiTap->ten_file ?: 'File đính kèm' }}
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            @if($baiTapDaNop)
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin nộp bài</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                                <dt class="text-sm font-medium text-gray-500">Ngày nộp</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($baiTapDaNop->ngay_nop)->format('d/m/Y H:i:s') }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                                <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($baiTapDaNop->trang_thai == 'da_nop')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Đã nộp - Chờ chấm điểm
                                        </span>
                                    @elseif($baiTapDaNop->trang_thai == 'da_cham')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Đã chấm điểm
                                        </span>
                                    @elseif($baiTapDaNop->trang_thai == 'chua_hoan_thanh')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Chưa hoàn thành
                                        </span>
                                    @elseif($baiTapDaNop->trang_thai == 'qua_han')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Quá hạn
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($baiTapDaNop->trang_thai == 'da_cham')
                                <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Điểm</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $baiTapDaNop->diem ?? 'Chưa có điểm' }}
                                    </dd>
                                </div>
                            @endif
                            <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Hành động</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('hoc-vien.bai-tap.ket-qua', ['id' => $baiTapDaNop->id]) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-2"></i> Xem kết quả
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @else
                <div class="rounded-md bg-yellow-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Bạn chưa làm bài tập này!
                            </h3>
                            
                            @if($baiTap->han_nop && now() > $baiTap->han_nop)
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Bài tập đã hết hạn nộp.</p>
                                </div>
                            @else
                                <div class="mt-4">
                                    @if($baiTap->loai == 'tu_luan')
                                        <a href="{{ route('hoc-vien.bai-tap.lam-bai-tu-luan', ['id' => $baiTap->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-edit mr-2"></i> Làm bài tự luận
                                        </a>
                                    @else
                                        <a href="{{ route('hoc-vien.bai-tap.form-nop-bai', ['id' => $baiTap->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-upload mr-2"></i> Nộp bài
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 