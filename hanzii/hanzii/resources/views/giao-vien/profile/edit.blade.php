@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa thông tin cá nhân')
@section('page-heading', 'Chỉnh sửa thông tin cá nhân')

@php
    $active = 'profile';
    $role = 'giao_vien';
@endphp

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Chỉnh sửa thông tin cá nhân</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Cập nhật thông tin chi tiết về tài khoản của bạn.</p>
    </div>
    
    <div class="border-t border-gray-200">
        <form action="{{ route('giao-vien.profile.update') }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Phần upload ảnh đại diện -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                    <div class="mt-1 flex items-center">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100">
                            @if($nguoiDung->anh_dai_dien)
                                <img src="{{ asset('storage/' . $nguoiDung->anh_dai_dien) }}" alt="Ảnh đại diện" class="h-full w-full object-cover">
                            @else
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>
                        <input type="file" name="anh_dai_dien" id="anh_dai_dien" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    </div>
                    @error('anh_dai_dien')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="ho" class="block text-sm font-medium text-gray-700">Họ <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="ho" id="ho" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('ho', $nguoiDung->ho) }}" required>
                    </div>
                    @error('ho')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="ten" class="block text-sm font-medium text-gray-700">Tên <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="ten" id="ten" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('ten', $nguoiDung->ten) }}" required>
                    </div>
                    @error('ten')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('email', $nguoiDung->email) }}" required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('so_dien_thoai', $nguoiDung->so_dien_thoai) }}" required>
                    </div>
                    @error('so_dien_thoai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="dia_chi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                    <div class="mt-1">
                        <input type="text" name="dia_chi" id="dia_chi" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('dia_chi', $nguoiDung->dia_chi) }}">
                    </div>
                    @error('dia_chi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="bang_cap" class="block text-sm font-medium text-gray-700">Bằng cấp</label>
                    <div class="mt-1">
                        <textarea name="bang_cap" id="bang_cap" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('bang_cap', is_array($giaoVien->bang_cap) ? implode("\n", $giaoVien->bang_cap) : $giaoVien->bang_cap) }}</textarea>
                    </div>
                    @error('bang_cap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-4">
                    <label for="chuyen_mon" class="block text-sm font-medium text-gray-700">Chuyên môn</label>
                    <div class="mt-1">
                        <input type="text" name="chuyen_mon" id="chuyen_mon" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('chuyen_mon', $giaoVien->chuyen_mon) }}">
                    </div>
                    @error('chuyen_mon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="so_nam_kinh_nghiem" class="block text-sm font-medium text-gray-700">Số năm kinh nghiệm</label>
                    <div class="mt-1">
                        <input type="number" name="so_nam_kinh_nghiem" id="so_nam_kinh_nghiem" min="0" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('so_nam_kinh_nghiem', $giaoVien->so_nam_kinh_nghiem) }}">
                    </div>
                    @error('so_nam_kinh_nghiem')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('giao-vien.profile.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 