@extends('layouts.dashboard')

@section('title', 'Danh sách bài tập cần chấm điểm')
@section('page-heading', 'Danh sách bài tập cần chấm điểm')

@php
    $active = 'cham_diem';
    $role = 'giao_vien';
@endphp

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Danh sách bài tập cần chấm điểm</h1>
        <div class="mt-1 flex items-center">
            <a href="{{ route('giao-vien.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-600">Chấm điểm</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Thành công!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Lỗi!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tổng bài tập</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($thongKe['tong']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Chờ chấm</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($thongKe['cho_cham']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đã chấm</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($thongKe['da_cham']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tỷ lệ chấm</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $thongKe['tong'] > 0 ? number_format($thongKe['da_cham'] / $thongKe['tong'] * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Bộ lọc</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('giao-vien.cham-diem.index') }}" method="GET" class="flex flex-wrap items-end space-y-4 md:space-y-0">
                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                    <label for="lop_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
                    <select id="lop_hoc_id" name="lop_hoc_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Tất cả lớp học</option>
                        @foreach($lopHocs as $lop)
                            <option value="{{ $lop->id }}" {{ request('lop_hoc_id') == $lop->id ? 'selected' : '' }}>
                                {{ $lop->ten }} ({{ $lop->ma_lop }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
                    <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select id="trang_thai" name="trang_thai" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Tất cả trạng thái</option>
                        <option value="da_nop" {{ request('trang_thai') == 'da_nop' ? 'selected' : '' }}>Chờ chấm</option>
                        <option value="dang_cham" {{ request('trang_thai') == 'dang_cham' ? 'selected' : '' }}>Đang chấm</option>
                        <option value="da_cham" {{ request('trang_thai') == 'da_cham' ? 'selected' : '' }}>Đã chấm</option>
                    </select>
                </div>
                
                <div class="w-full md:w-1/3 px-2 flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Lọc
                    </button>
                    
                    <a href="{{ route('giao-vien.cham-diem.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách bài tập -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-clipboard-check mr-2"></i> Danh sách bài tập đã nộp
            </h3>
        </div>
        
        @if($baiNops->count() > 0)
            <div class="border-t border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Học viên
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bài tập
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lớp học
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày nộp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($baiNops as $baiNop)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $baiNop->hocVien->nguoiDung->ho_ten }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $baiNop->hocVien->nguoiDung->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $baiNop->baiTap->tieu_de }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($baiNop->baiTap->loai == 'tu_luan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Tự luận
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                File
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if(isset($baiNop->baiTap) && isset($baiNop->baiTap->baiHoc) && isset($baiNop->baiTap->baiHoc->baiHocLops) && $baiNop->baiTap->baiHoc->baiHocLops->isNotEmpty() && isset($baiNop->baiTap->baiHoc->baiHocLops->first()->lopHoc))
                                            {{ $baiNop->baiTap->baiHoc->baiHocLops->first()->lopHoc->ten }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($baiNop->ngay_nop)->format('d/m/Y H:i') }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($baiNop->ngay_nop)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($baiNop->trang_thai == 'da_nop')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Chờ chấm
                                        </span>
                                    @elseif($baiNop->trang_thai == 'dang_cham')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Đang chấm
                                        </span>
                                    @elseif($baiNop->trang_thai == 'da_cham')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Đã chấm ({{ $baiNop->diem }}/10)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $baiNop->trang_thai }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('giao-vien.cham-diem.tu-luan', $baiNop->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-check-circle mr-1"></i> Chấm điểm
                                    </a>
                                    
                                    @if($baiNop->file_path)
                                        <a href="{{ route('giao-vien.cham-diem.download', $baiNop->id) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-download mr-1"></i> Tải file
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $baiNops->appends(request()->query())->links() }}
            </div>
        @else
            <div class="py-12 flex flex-col items-center justify-center border-t border-gray-200">
                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <p class="mt-4 text-gray-500 text-lg">Không có bài tập nào cần chấm điểm</p>
                <p class="mt-2 text-gray-400 text-base">Quay lại sau khi có học viên nộp bài</p>
            </div>
        @endif
    </div>
</div>
@endsection
