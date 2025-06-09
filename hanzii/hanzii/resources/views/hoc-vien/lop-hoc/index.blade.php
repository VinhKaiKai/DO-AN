@extends('layouts.dashboard')

@section('title', 'Danh sách lớp học')
@section('page-heading', 'Danh sách lớp học')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Danh sách lớp học của bạn</h2>
        
        <div class="flex space-x-2">
        
            
            <a href="{{ route('hoc-vien.lop-hoc.form-tim-kiem') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Tìm lớp học
            </a>
        </div>
    </div>

    <!-- Tìm kiếm và lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('hoc-vien.lop-hoc.index') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <div class="flex-1">
                <input type="text" name="keyword" placeholder="Tên lớp học hoặc mã lớp" value="{{ request('keyword') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            <div class="flex-1">
                <select name="trang_thai" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="sap_khai_giang" {{ request('trang_thai') == 'sap_khai_giang' ? 'selected' : '' }}>Sắp khai giảng</option>
                    <option value="da_hoan_thanh" {{ request('trang_thai') == 'da_hoan_thanh' ? 'selected' : '' }}>Đã hoàn thành</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Lọc kết quả
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách tất cả lớp học -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tất cả lớp học của bạn</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($lopHocs as $lopHoc)
                    <div class="border rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <div class="border-l-4 
                            @if($lopHoc->trang_thai == 'dang_dien_ra') border-blue-500 
                            @elseif($lopHoc->trang_thai == 'sap_khai_giang') border-yellow-500 
                            @elseif($lopHoc->trang_thai == 'da_hoan_thanh') border-gray-500 
                            @else border-green-500 @endif 
                            h-full flex flex-col">
                            <div class="p-5 flex-grow">
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $lopHoc->ten }}</h4>
                                <p class="text-xs 
                                    @if($lopHoc->trang_thai == 'dang_dien_ra') text-blue-600 
                                    @elseif($lopHoc->trang_thai == 'sap_khai_giang') text-yellow-600 
                                    @elseif($lopHoc->trang_thai == 'da_hoan_thanh') text-gray-600 
                                    @else text-green-600 @endif mb-2">
                                    Mã lớp: {{ $lopHoc->ma_lop }}
                                </p>
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Khóa học:</span> 
                                    {{ $lopHoc->khoaHoc->ten ?? 'Chưa có thông tin' }}
                                </p>
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Giáo viên:</span> 
                                    {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? ($lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten ?? 'Chưa phân công') }}
                                </p>
                                <p class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">Thời gian:</span> 
                                    {{ isset($lopHoc->ngay_bat_dau) ? \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') : 'Chưa xác định' }}
                                    @if(isset($lopHoc->ngay_ket_thuc)) 
                                        - {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}
                                    @endif
                                </p>
                                <div class="mb-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($lopHoc->trang_thai == 'dang_dien_ra') bg-blue-100 text-blue-800
                                        @elseif($lopHoc->trang_thai == 'sap_khai_giang') bg-yellow-100 text-yellow-800
                                        @elseif($lopHoc->trang_thai == 'da_hoan_thanh') bg-gray-100 text-gray-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if($lopHoc->trang_thai == 'dang_dien_ra') Đang diễn ra
                                        @elseif($lopHoc->trang_thai == 'sap_khai_giang') Sắp khai giảng
                                        @elseif($lopHoc->trang_thai == 'da_hoan_thanh') Đã hoàn thành
                                        @else Đang hoạt động @endif
                                    </span>
                                    @if(isset($lopHoc->hinh_thuc_hoc) && $lopHoc->hinh_thuc_hoc == 'online')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Trực tuyến
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Trực tiếp
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('hoc-vien.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-gray-500">Bạn chưa tham gia lớp học nào</p>
                        <div class="mt-3">
                            <a href="{{ route('hoc-vien.lop-hoc.form-tim-kiem') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Tìm lớp học ngay
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Các script khác nếu cần thiết -->
@endpush 