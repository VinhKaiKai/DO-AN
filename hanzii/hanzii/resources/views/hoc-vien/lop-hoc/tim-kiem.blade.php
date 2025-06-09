@extends('layouts.dashboard')

@section('title', 'Tìm kiếm lớp học')
@section('page-heading', 'Tìm kiếm lớp học')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Kết quả tìm kiếm lớp học</h2>
            <a href="{{ route('hoc-vien.lop-hoc.index') }}" class="text-red-600 hover:text-red-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách lớp học
            </a>
        </div>
        
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 flex items-start">
            <div class="mr-4 flex-shrink-0">
                <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</h3>
                <div class="mt-1 flex flex-wrap items-center">
                    @if($lopHoc->trang_thai == 'dang_dien_ra')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mr-2">
                            Đang diễn ra
                        </span>
                    @elseif($lopHoc->trang_thai == 'sap_khai_giang')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-2">
                            Sắp khai giảng
                        </span>
                    @elseif($lopHoc->trang_thai == 'da_ket_thuc')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 mr-2">
                            Đã kết thúc
                        </span>
                    @endif
                    
                    @if($lopHoc->hinh_thuc == 'online')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            Online
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            Offline
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="mr-3 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Giáo viên</p>
                        <p class="text-md font-medium">{{ $lopHoc->giaoVien->ho_ten }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="mr-3 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Khoá học</p>
                        <p class="text-md font-medium">{{ $lopHoc->khoaHoc->ten }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="mr-3 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Học viên</p>
                        <p class="text-md font-medium">{{ $lopHoc->hocViens->count() }} / {{ $lopHoc->so_luong_toi_da }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center mb-2">
                    <div class="mr-3 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Thời gian học</p>
                    </div>
                </div>
                <div class="pl-9">
                    <p class="text-sm text-gray-600">{{ $lopHoc->lich_hoc }}</p>
                </div>
            </div>
            
        
        </div>
        
        <div class="border-t border-gray-200 pt-6 mb-6">
            @if($daLaThanhVien)
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Bạn đã là thành viên của lớp học này.
                                <a href="{{ route('hoc-vien.lop-hoc.show', $lopHoc->id) }}" class="font-medium underline text-green-700 hover:text-green-600">
                                    Xem chi tiết lớp học
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($daGuiYeuCau)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Bạn đã gửi yêu cầu tham gia lớp học này và đang chờ phê duyệt.
                                <a href="{{ route('hoc-vien.lop-hoc.yeu-cau') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                    Xem yêu cầu đã gửi
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($lopHoc->trang_thai == 'da_ket_thuc')
                <div class="bg-gray-50 border-l-4 border-gray-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700">
                                Lớp học này đã kết thúc nên không thể tham gia.
                            </p>
                        </div>
                    </div>
                </div>
            
            @else
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gửi yêu cầu tham gia</h3>
                <form action="{{ route('hoc-vien.lop-hoc.gui-yeu-cau') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lop_hoc_id" value="{{ $lopHoc->id }}">
                    
                    <div class="mb-4">
                        <label for="ghi_chu" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú (không bắt buộc)</label>
                        <textarea id="ghi_chu" name="ghi_chu" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nhập ghi chú hoặc lý do bạn muốn tham gia lớp học này..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="dong_y_dieu_khoan" name="dong_y_dieu_khoan" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded" required>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="dong_y_dieu_khoan" class="font-medium text-gray-700">Tôi đồng ý với nội quy lớp học</label>
                                <p class="text-gray-500">Tôi cam kết sẽ tuân thủ nội quy và quy định của lớp học. Tôi hiểu rằng yêu cầu tham gia cần được giáo viên phê duyệt.</p>
                            </div>
                        </div>
                        @error('dong_y_dieu_khoan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Gửi yêu cầu tham gia
                        </button>
                    </div>
                </form>
            @endif
        </div>
        
        @if(!empty($lopHoc->mo_ta))
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mô tả lớp học</h3>
                <div class="prose prose-red max-w-none">
                    {!! $lopHoc->mo_ta !!}
                </div>
            </div>
        @endif
    </div>
@endsection 