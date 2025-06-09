@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa thông tin cá nhân')
@section('page-heading', 'Chỉnh sửa thông tin cá nhân')

@php
    $active = 'nguoi-dung';
    $role = 'admin';
@endphp

@section('content')
<div class="w-full">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0 flex flex-col items-center">
                    <div class="w-48 h-48 bg-gray-200 rounded-full overflow-hidden mb-4 relative group">
                        @if ($nguoiDung->anh_dai_dien)
                            <img src="{{ asset('storage/' . $nguoiDung->anh_dai_dien) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-4xl font-bold">
                                {{ substr($nguoiDung->ho, 0, 1) }}{{ substr($nguoiDung->ten, 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <label for="anh_dai_dien" class="cursor-pointer text-white">
                                <i class="fas fa-camera text-2xl"></i>
                            </label>
                            <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="hidden" accept="image/*">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Nhấp vào ảnh để thay đổi</p>
                    
                    <a href="{{ route('admin.profile.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại
                    </a>
                </div>
                
                <div class="md:w-2/3 md:pl-8">
                    <h4 class="text-lg font-semibold mb-4">Thông tin cá nhân</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="ho" class="block text-sm font-medium text-gray-700 mb-1">Họ</label>
                            <input type="text" name="ho" id="ho" value="{{ old('ho', $nguoiDung->ho) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('ho')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="ten" class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
                            <input type="text" name="ten" id="ten" value="{{ old('ten', $nguoiDung->ten) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('ten')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $nguoiDung->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai', $nguoiDung->so_dien_thoai) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('so_dien_thoai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('anh_dai_dien').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(event) {
                let preview = e.target.closest('.relative').querySelector('img');
                if (!preview) {
                    // Nếu không có ảnh, tạo một ảnh mới
                    preview = document.createElement('img');
                    preview.className = 'w-full h-full object-cover';
                    e.target.closest('.relative').querySelector('div').replaceWith(preview);
                }
                preview.src = event.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush 