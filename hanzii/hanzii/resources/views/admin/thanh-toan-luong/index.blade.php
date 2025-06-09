@extends('layouts.dashboard')

@section('title', 'Quản lý thanh toán lương')
@section('page-heading', 'Quản lý thanh toán lương')

@php
    $active = 'thanh-toan-luong';
    $role = 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-red-500">
            <h6 class="text-sm font-medium text-gray-500">Tổng lớp đã kết thúc</h6>
            <p class="text-2xl font-semibold mt-2">{{ $tongLopDaKetThuc }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-green-500">
            <h6 class="text-sm font-medium text-gray-500">Lớp đã trả lương</h6>
            <p class="text-2xl font-semibold mt-2">{{ $tongLopDaTraLuong }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-yellow-500">
            <h6 class="text-sm font-medium text-gray-500">Lớp chưa trả lương</h6>
            <p class="text-2xl font-semibold mt-2">{{ $tongLopChuaTraLuong }}</p>
        </div>
    </div>

    <!-- Form tìm kiếm -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <h5 class="text-lg font-medium text-gray-900 mb-4">Tìm kiếm</h5>
        <form action="{{ route('admin.thanh-toan-luong.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Tên lớp/Mã lớp</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Nhập tên hoặc mã lớp..." value="{{ request('search') }}">
                    </div>
                </div>
                <div>
                    <label for="trang_thai" class="block text-sm font-medium text-gray-700">Trạng thái thanh toán</label>
                    <select name="trang_thai" id="trang_thai" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Tất cả</option>
                        <option value="da_thanh_toan" {{ request('trang_thai') == 'da_thanh_toan' ? 'selected' : '' }}>Đã trả lương</option>
                        <option value="chua_thanh_toan" {{ request('trang_thai') == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa trả lương</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.thanh-toan-luong.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-2">
                        <i class="fas fa-sync-alt mr-2"></i> Đặt lại
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bảng danh sách lớp -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h5 class="text-lg font-medium text-gray-900">Danh sách lớp đã kết thúc</h5>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên lớp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trợ giảng</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lopHocs as $lopHoc)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ma_lop }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->daThanhToanDayDu())
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1.5"></i> Đã trả lương
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1.5"></i> Chưa trả lương
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa có' }}
                                @if($lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->trang_thai_giao_vien === 'da_thanh_toan')
                                    <span class="ml-1 text-green-500">
                                        <i class="fas fa-check-circle" title="Đã trả lương"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa có' }}
                                @if($lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->trang_thai_tro_giang === 'da_thanh_toan')
                                    <span class="ml-1 text-green-500">
                                        <i class="fas fa-check-circle" title="Đã trả lương"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.thanh-toan-luong.show', $lopHoc->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-eye mr-1.5"></i> Chi tiết trả lương
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Không có lớp học nào đã kết thúc
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 