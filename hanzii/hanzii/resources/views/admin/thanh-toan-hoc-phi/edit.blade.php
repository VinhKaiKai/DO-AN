@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa thanh toán học phí')
@section('page-heading', 'Chỉnh sửa thanh toán học phí')

@php
    $active = 'thanh-toan-hoc-phi';
    $role = 'admin';
@endphp

@push('styles')
<style>
    /* CSS bổ sung nếu cần */
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Hiển thị thông báo -->
    @if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-medium">Thành công!</strong>
        <span class="block sm:inline ml-1">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-medium">Lỗi!</strong>
        <span class="block sm:inline ml-1">{{ session('error') }}</span>
    </div>
    @endif

    <div class="flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Chỉnh sửa thanh toán học phí</h2>
        <a href="{{ route('admin.thanh-toan-hoc-phi.show', $thanhToanHocPhi->lop_hoc_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <!-- Form chỉnh sửa thanh toán học phí -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.thanh-toan-hoc-phi.update', $thanhToanHocPhi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="lop_hoc_id" value="{{ $thanhToanHocPhi->lop_hoc_id }}">
            <input type="hidden" name="hoc_vien_id" value="{{ $thanhToanHocPhi->hoc_vien_id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin học viên -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Học viên</label>
                    <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ $thanhToanHocPhi->hocVien->hoTen }}" readonly>
                </div>
                
                <!-- Thông tin lớp học -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
                    <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ $thanhToanHocPhi->lopHoc->ten }}" readonly>
                </div>
                
                <!-- Số tiền -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền</label>
                    <input type="number" name="so_tien" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ $thanhToanHocPhi->so_tien }}" required>
                    @error('so_tien')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phương thức thanh toán -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                    <select name="phuong_thuc_thanh_toan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        <option value="tien_mat" {{ $thanhToanHocPhi->phuong_thuc_thanh_toan == 'tien_mat' ? 'selected' : '' }}>Tiền mặt</option>
                        <option value="chuyen_khoan" {{ $thanhToanHocPhi->phuong_thuc_thanh_toan == 'chuyen_khoan' ? 'selected' : '' }}>Chuyển khoản</option>
                    </select>
                    @error('phuong_thuc_thanh_toan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Trạng thái -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="trang_thai" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        <option value="chua_thanh_toan" {{ $thanhToanHocPhi->trang_thai == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="da_thanh_toan" {{ $thanhToanHocPhi->trang_thai == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="da_huy" {{ $thanhToanHocPhi->trang_thai == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    @error('trang_thai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ngày thanh toán -->
                <div class="col-span-1 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày thanh toán</label>
                    <input type="date" name="ngay_thanh_toan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ $thanhToanHocPhi->ngay_thanh_toan ? date('Y-m-d', strtotime($thanhToanHocPhi->ngay_thanh_toan)) : '' }}">
                    @error('ngay_thanh_toan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ghi chú -->
                <div class="col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="ghi_chu" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" rows="3">{{ $thanhToanHocPhi->ghi_chu }}</textarea>
                    @error('ghi_chu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.thanh-toan-hoc-phi.show', $thanhToanHocPhi->lop_hoc_id) }}" class="bg-gray-200 text-gray-800 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-3">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
