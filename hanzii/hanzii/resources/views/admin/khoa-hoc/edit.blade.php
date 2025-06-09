@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa khóa học')
@section('page-heading', 'Chỉnh sửa khóa học')

@php
    $active = 'khoa-hoc';
    $role = 'admin';
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Chỉnh sửa khóa học</h2>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.khoa-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Thông tin khóa học</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.khoa-hoc.update', $khoaHoc->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="ten" class="block text-sm font-medium text-gray-700 mb-1">Tên khóa học <span class="text-red-600">*</span></label>
                        <input type="text" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('ten') border-red-500 @enderror" id="ten" name="ten" value="{{ old('ten', $khoaHoc->ten) }}" required>
                        @error('ten')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="hoc_phi" class="block text-sm font-medium text-gray-700 mb-1">Học phí <span class="text-red-600">*</span></label>
                        <input type="number" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('hoc_phi') border-red-500 @enderror" id="hoc_phi" name="hoc_phi" value="{{ old('hoc_phi', $khoaHoc->hoc_phi) }}" required>
                        @error('hoc_phi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="thoi_gian_hoan_thanh" class="block text-sm font-medium text-gray-700 mb-1">Thời gian hoàn thành <span class="text-red-600">*</span></label>
                        <input type="text" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('thoi_gian_hoan_thanh') border-red-500 @enderror" id="thoi_gian_hoan_thanh" name="thoi_gian_hoan_thanh" value="{{ old('thoi_gian_hoan_thanh', $khoaHoc->thoi_gian_hoan_thanh) }}" placeholder="Ví dụ: 3 tháng">
                        @error('thoi_gian_hoan_thanh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-600">*</span></label>
                        <select class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('trang_thai') border-red-500 @enderror" id="trang_thai" name="trang_thai" required>
                            <option value="hoat_dong" {{ old('trang_thai', $khoaHoc->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="tam_ngung" {{ old('trang_thai', $khoaHoc->trang_thai) == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                            <option value="da_ket_thuc" {{ old('trang_thai', $khoaHoc->trang_thai) == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                        @error('trang_thai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="tong_so_bai" class="block text-sm font-medium text-gray-700 mb-1">Tổng số bài học</label>
                        <input type="number" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('tong_so_bai') border-red-500 @enderror" id="tong_so_bai" name="tong_so_bai" value="{{ old('tong_so_bai', $khoaHoc->tong_so_bai) }}">
                        @error('tong_so_bai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="hinh_anh" class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh</label>
                        <div class="mt-1 flex items-center">
                            <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*" class="hidden" onchange="updateFileLabel(this)">
                            <label for="hinh_anh" class="relative cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span id="file-label">Chọn hình ảnh mới (nếu cần)</span>
                            </label>
                        </div>
                        @if($khoaHoc->hinh_anh)
                            <div class="mt-2">
                                <img src="{{ Storage::url($khoaHoc->hinh_anh) }}" alt="{{ $khoaHoc->ten }}" class="h-24 w-auto object-cover rounded">
                                <div class="mt-1 flex items-center">
                                    <input id="xoa_hinh_anh" name="xoa_hinh_anh" type="checkbox" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <label for="xoa_hinh_anh" class="ml-2 block text-sm text-gray-700">
                                        Xóa hình ảnh hiện tại
                                    </label>
                                </div>
                            </div>
                        @endif
                        @error('hinh_anh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="mo_ta" class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết <span class="text-red-600">*</span></label>
                    <textarea class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('mo_ta') border-red-500 @enderror" id="mo_ta" name="mo_ta" rows="5" required>{{ old('mo_ta', $khoaHoc->mo_ta) }}</textarea>
                    @error('mo_ta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex flex-wrap justify-start space-x-0 sm:space-x-4 space-y-4 sm:space-y-0">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i> Cập nhật khóa học
                    </button>
                    <a href="{{ route('admin.khoa-hoc.show', $khoaHoc->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-eye mr-2"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.khoa-hoc.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hiển thị tên file khi chọn
    function updateFileLabel(input) {
        const fileName = input.files[0]?.name || 'Chọn hình ảnh mới (nếu cần)';
        document.getElementById('file-label').textContent = fileName;
    }
</script>
@endpush 