@extends('layouts.dashboard')

@section('title', 'Cập nhật thông tin cá nhân')
@section('page-heading', 'Cập nhật thông tin cá nhân')

@php
    $active = 'profile';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Cập nhật thông tin cá nhân</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Cập nhật thông tin cá nhân của bạn</p>
        </div>
        
        <div class="border-t border-gray-200">
            <form action="{{ route('hoc-vien.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="anh_dai_dien" class="block text-sm font-medium text-gray-700 mb-2">Ảnh đại diện</label>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                                @if($nguoiDung->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $nguoiDung->anh_dai_dien) }}" alt="Avatar" class="h-full w-full object-cover" id="preview">
                                @else
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24" id="preview-placeholder">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <img src="" alt="Avatar" class="h-full w-full object-cover hidden" id="preview">
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="file" name="anh_dai_dien" id="anh_dai_dien" class="sr-only" accept="image/*" onchange="previewImage()">
                            <label for="anh_dai_dien" class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 cursor-pointer">
                                Chọn ảnh
                            </label>
                            <p class="ml-3 text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                        </div>
                    </div>
                    @error('anh_dai_dien')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ho" class="block text-sm font-medium text-gray-700 mb-2">Họ</label>
                        <input type="text" name="ho" id="ho" value="{{ old('ho', $nguoiDung->ho) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        @error('ho')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="ten" class="block text-sm font-medium text-gray-700 mb-2">Tên</label>
                        <input type="text" name="ten" id="ten" value="{{ old('ten', $nguoiDung->ten) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        @error('ten')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $nguoiDung->email) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai', $nguoiDung->so_dien_thoai) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    @error('so_dien_thoai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="ngay_sinh" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" id="ngay_sinh" value="{{ old('ngay_sinh', $hocVien->ngay_sinh ? \Carbon\Carbon::parse($hocVien->ngay_sinh)->format('Y-m-d') : '') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    @error('ngay_sinh')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="dia_chi" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                    <textarea name="dia_chi" id="dia_chi" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">{{ old('dia_chi', $nguoiDung->dia_chi) }}</textarea>
                    @error('dia_chi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('hoc-vien.profile.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-3">
                        Hủy
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function previewImage() {
        const file = document.getElementById('anh_dai_dien').files[0];
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('preview-placeholder');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection 