@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa trợ giảng')
@section('page-heading', 'Chỉnh sửa trợ giảng')

@php
    $active = 'tro-giang';
    $role = 'admin';
    $chuyenMonArr = explode(',', $troGiang->chuyen_mon ?? '');
@endphp

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Chỉnh sửa trợ giảng: {{ $troGiang->nguoiDung->ho_ten }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.tro-giang.show', $troGiang->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-eye mr-2"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.tro-giang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.tro-giang.update', $troGiang->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin cá nhân -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin cá nhân</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="ho" class="block text-sm font-medium text-gray-700">Họ</label>
                                <input type="text" name="ho" id="ho" value="{{ old('ho', $troGiang->nguoiDung->ho) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ho')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="ten" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" name="ten" id="ten" value="{{ old('ten', $troGiang->nguoiDung->ten) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ten')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $troGiang->nguoiDung->email) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai', $troGiang->nguoiDung->so_dien_thoai) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('so_dien_thoai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="dia_chi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                <input type="text" name="dia_chi" id="dia_chi" value="{{ old('dia_chi', $troGiang->nguoiDung->dia_chi) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('dia_chi')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin chuyên môn -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin chuyên môn</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="bang_cap" class="block text-sm font-medium text-gray-700">Bằng cấp</label>
                                <select name="bang_cap" id="bang_cap" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">-- Chọn bằng cấp --</option>
                                    <option value="dai_hoc" {{ old('bang_cap', $troGiang->bang_cap) == 'dai_hoc' ? 'selected' : '' }}>Đại học</option>
                                    <option value="thac_si" {{ old('bang_cap', $troGiang->bang_cap) == 'thac_si' ? 'selected' : '' }}>Thạc sĩ</option>
                                    <option value="tien_si" {{ old('bang_cap', $troGiang->bang_cap) == 'tien_si' ? 'selected' : '' }}>Tiến sĩ</option>
                                    <option value="khac" {{ old('bang_cap', $troGiang->bang_cap) == 'khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('bang_cap')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="so_nam_kinh_nghiem" class="block text-sm font-medium text-gray-700">Số năm kinh nghiệm</label>
                                <input type="number" name="so_nam_kinh_nghiem" id="so_nam_kinh_nghiem" value="{{ old('so_nam_kinh_nghiem', $troGiang->so_nam_kinh_nghiem) }}" min="0" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('so_nam_kinh_nghiem')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="chuyen_mon" class="block text-sm font-medium text-gray-700">Chuyên môn</label>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk1" id="chuyen_mon_hsk1" value="hsk1" {{ in_array('hsk1', $chuyenMonArr) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk1" class="ml-2 block text-sm text-gray-700">HSK 1</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk2" id="chuyen_mon_hsk2" value="hsk2" {{ in_array('hsk2', $chuyenMonArr) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk2" class="ml-2 block text-sm text-gray-700">HSK 2</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk3" id="chuyen_mon_hsk3" value="hsk3" {{ in_array('hsk3', $chuyenMonArr) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk3" class="ml-2 block text-sm text-gray-700">HSK 3</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk4" id="chuyen_mon_hsk4" value="hsk4" {{ in_array('hsk4', $chuyenMonArr) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk4" class="ml-2 block text-sm text-gray-700">HSK 4</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk5" id="chuyen_mon_hsk5" value="hsk5" {{ in_array('hsk5', $chuyenMonArr) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk5" class="ml-2 block text-sm text-gray-700">HSK 5</label>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="chuyen_mon" id="chuyen_mon" value="{{ old('chuyen_mon', $troGiang->chuyen_mon) }}">
                                
                                @error('chuyen_mon')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="processCacChuyenMon()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-save mr-2"></i> Cập nhật trợ giảng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function processCacChuyenMon() {
        // Lấy các checkbox đã chọn
        const chuyenMonArr = [];
        
        if (document.getElementById('chuyen_mon_hsk1').checked) chuyenMonArr.push('hsk1');
        if (document.getElementById('chuyen_mon_hsk2').checked) chuyenMonArr.push('hsk2');
        if (document.getElementById('chuyen_mon_hsk3').checked) chuyenMonArr.push('hsk3');
        if (document.getElementById('chuyen_mon_hsk4').checked) chuyenMonArr.push('hsk4');
        if (document.getElementById('chuyen_mon_hsk5').checked) chuyenMonArr.push('hsk5');
        
        // Gán giá trị vào input hidden
        document.getElementById('chuyen_mon').value = chuyenMonArr.join(',');
        
        // Submit form
        document.querySelector('form').submit();
    }
</script>
@endpush 