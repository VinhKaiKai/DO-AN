@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa lớp học')
@section('page-heading', 'Chỉnh sửa lớp học')

@php
    $active = 'lop-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Chỉnh sửa lớp học: {{ $lopHoc->ma_lop }}</h2>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 disabled:opacity-25 transition">
                    <i class="fas fa-eye mr-2"></i> Xem chi tiết
                </a>
                <a href="{{ route('admin.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form chỉnh sửa lớp học -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('admin.lop-hoc.update', $lopHoc->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin cơ bản -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin lớp học</h3>
                        
                        <div class="mb-4">
                            <label for="ten" class="block text-sm font-medium text-gray-700 mb-1">Tên lớp học <span class="text-red-600">*</span></label>
                            <input type="text" name="ten" id="ten" value="{{ old('ten', $lopHoc->ten) }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Nhập tên lớp học">
                            @error('ten')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="ma_lop" class="block text-sm font-medium text-gray-700 mb-1">Mã lớp <span class="text-red-600">*</span></label>
                            <input type="text" name="ma_lop" id="ma_lop" value="{{ old('ma_lop', $lopHoc->ma_lop) }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Nhập mã lớp học (VD: JAVA-01)">
                            @error('ma_lop')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="khoa_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Khóa học <span class="text-red-600">*</span></label>
                            <select id="khoa_hoc_id" name="khoa_hoc_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">-- Chọn khóa học ({{ count($khoaHocs) }} khóa học) --</option>
                                @foreach($khoaHocs as $id => $ten)
                                    <option value="{{ $id }}" {{ old('khoa_hoc_id', $lopHoc->khoa_hoc_id) == $id ? 'selected' : '' }}>{{ $ten }}</option>
                                @endforeach
                            </select>
                            @error('khoa_hoc_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="so_luong_toi_da" class="block text-sm font-medium text-gray-700 mb-1">Số lượng học viên tối đa <span class="text-red-600">*</span></label>
                            <input type="number" name="so_luong_toi_da" id="so_luong_toi_da" value="{{ old('so_luong_toi_da', $lopHoc->so_luong_toi_da) }}" required min="1" max="100" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Số học viên hiện tại: {{ $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count() }}</p>
                            @error('so_luong_toi_da')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-600">*</span></label>
                            <select id="trang_thai" name="trang_thai" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="sap_khai_giang" {{ old('trang_thai', $lopHoc->trang_thai) == 'sap_khai_giang' ? 'selected' : '' }}>Sắp khai giảng</option>
                                <option value="dang_dien_ra" {{ old('trang_thai', $lopHoc->trang_thai) == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                                <option value="da_ket_thuc" {{ old('trang_thai', $lopHoc->trang_thai) == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                            @error('trang_thai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Thông tin học tập -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin lịch học</h3>
                        
                        <div class="mb-4">
                            <label for="hinh_thuc_hoc" class="block text-sm font-medium text-gray-700 mb-1">Hình thức học <span class="text-red-600">*</span></label>
                            <select id="hinh_thuc_hoc" name="hinh_thuc_hoc" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="online" {{ old('hinh_thuc_hoc', $lopHoc->hinh_thuc_hoc) == 'online' ? 'selected' : '' }}>Trực tuyến</option>
                                <option value="offline" {{ old('hinh_thuc_hoc', $lopHoc->hinh_thuc_hoc) == 'offline' ? 'selected' : '' }}>Tại trung tâm</option>
                            </select>
                            @error('hinh_thuc_hoc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="lich_hoc" class="block text-sm font-medium text-gray-700 mb-1">Lịch học <span class="text-red-600">*</span></label>
                            <input type="text" name="lich_hoc" id="lich_hoc" value="{{ old('lich_hoc', $lopHoc->lich_hoc) }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="VD: Thứ 2, 4, 6 - 18h30-21h00">
                            @error('lich_hoc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="ngay_bat_dau" class="block text-sm font-medium text-gray-700 mb-1">Ngày bắt đầu <span class="text-red-600">*</span></label>
                            <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" value="{{ old('ngay_bat_dau', $lopHoc->ngay_bat_dau ? date('Y-m-d', strtotime($lopHoc->ngay_bat_dau)) : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            @error('ngay_bat_dau')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="ngay_ket_thuc" class="block text-sm font-medium text-gray-700 mb-1">Ngày kết thúc <span class="text-red-600">*</span></label>
                            <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" value="{{ old('ngay_ket_thuc', $lopHoc->ngay_ket_thuc ? date('Y-m-d', strtotime($lopHoc->ngay_ket_thuc)) : '') }}" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            @error('ngay_ket_thuc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="giao_vien_id" class="block text-sm font-medium text-gray-700 mb-1">Giáo viên <span class="text-red-600">*</span></label>
                            <select id="giao_vien_id" name="giao_vien_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">-- Chọn giáo viên --</option>
                                @foreach($giaoViens as $id => $ten)
                                    <option value="{{ $id }}" {{ old('giao_vien_id', $lopHoc->giao_vien_id) == $id ? 'selected' : '' }}>{{ $ten }}</option>
                                @endforeach
                            </select>
                            @error('giao_vien_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="tro_giang_id" class="block text-sm font-medium text-gray-700 mb-1">Trợ giảng <span class="text-red-600">*</span></label>
                            <select id="tro_giang_id" name="tro_giang_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">-- Chọn trợ giảng --</option>
                                @foreach($troGiangs as $id => $ten)
                                    <option value="{{ $id }}" {{ old('tro_giang_id', $lopHoc->tro_giang_id) == $id ? 'selected' : '' }}>{{ $ten }}</option>
                                @endforeach
                            </select>
                            @error('tro_giang_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="ghi_chu" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                    <textarea name="ghi_chu" id="ghi_chu" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Thông tin bổ sung về lớp học...">{{ old('ghi_chu', $lopHoc->ghi_chu) }}</textarea>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 text-right">
                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-3">
                    <i class="fas fa-save mr-2"></i> Cập nhật lớp học
                </button>
            </div>
        </form>
    </div>
    
    <!-- Thông tin học viên đã đăng ký -->
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách học viên đã đăng ký ({{ $lopHoc->dangKyHocs->where('trang_thai', 'da_xac_nhan')->count() }}/{{ $lopHoc->so_luong_toi_da }})</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm text-gray-600">Quản lý học viên lớp học tại 
                    <a href="{{ route('admin.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="text-red-600 hover:text-red-800">
                        Danh sách học viên
                    </a>
                </p>
                <a href="{{ route('admin.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-users mr-2"></i> Xem tất cả học viên
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
    document.addEventListener('DOMContentLoaded', function() {
        const ngayBatDau = document.getElementById('ngay_bat_dau');
        const ngayKetThuc = document.getElementById('ngay_ket_thuc');
        
        function updateMinEndDate() {
            if (ngayBatDau.value) {
                ngayKetThuc.min = ngayBatDau.value;
                
                // Nếu ngày kết thúc trước ngày bắt đầu, cập nhật lại
                if (ngayKetThuc.value && ngayKetThuc.value < ngayBatDau.value) {
                    ngayKetThuc.value = ngayBatDau.value;
                }
            }
        }
        
        ngayBatDau.addEventListener('change', updateMinEndDate);
        
        // Thiết lập ban đầu
        updateMinEndDate();
    });
</script>
@endpush 