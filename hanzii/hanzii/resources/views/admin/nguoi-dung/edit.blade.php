@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa người dùng')
@section('page-heading', 'Chỉnh sửa người dùng')

@php
    $active = 'nguoi-dung';
    $role = 'admin';
@endphp

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Chỉnh sửa thông tin người dùng</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.nguoi-dung.show', $nguoiDung->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-eye mr-2"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.nguoi-dung.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.nguoi-dung.update', $nguoiDung->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin cá nhân -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin cá nhân</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="ho" class="block text-sm font-medium text-gray-700">Họ</label>
                                <input type="text" name="ho" id="ho" value="{{ old('ho', $nguoiDung->ho) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ho')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="ten" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" name="ten" id="ten" value="{{ old('ten', $nguoiDung->ten) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ten')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $nguoiDung->email) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai', $nguoiDung->so_dien_thoai) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('so_dien_thoai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới (để trống nếu không thay đổi)</label>
                                <input type="password" name="password" id="password" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('password')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="dia_chi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                <input type="text" name="dia_chi" id="dia_chi" value="{{ old('dia_chi', $nguoiDung->dia_chi) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('dia_chi')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin vai trò -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin vai trò</h4>
                        
                        <div class="mb-4">
                            <label for="vai_tro_ids" class="block text-sm font-medium text-gray-700">Vai trò</label>
                            <select name="vai_tro_ids[]" id="vai_tro_ids" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="toggleRoleFields()">
                                <option value="">-- Chọn vai trò --</option>
                                @foreach($vaiTros as $vaiTro)
                                    <option value="{{ $vaiTro->id }}" data-role-name="{{ $vaiTro->ten }}" {{ $nguoiDungVaiTroId == $vaiTro->id ? 'selected' : '' }}>
                                        {{ $vaiTro->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Thông tin học viên (hiển thị nếu là học viên) -->
                    @if($nguoiDung->loai_tai_khoan == 'hoc_vien' && isset($hocVien))
                        <div id="hoc_vien_info" class="col-span-2 bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin học viên</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="ngay_sinh" class="block text-sm font-medium text-gray-700">Ngày sinh</label>
                                    <input type="date" name="ngay_sinh" id="ngay_sinh" value="{{ old('ngay_sinh', $hocVien->ngay_sinh) }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('ngay_sinh')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="trinh_do_hoc_van" class="block text-sm font-medium text-gray-700">Trình độ học vấn</label>
                                    <select name="trinh_do_hoc_van" id="trinh_do_hoc_van" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">-- Chọn trình độ --</option>
                                        <option value="trung_hoc" {{ old('trinh_do_hoc_van', $hocVien->trinh_do_hoc_van) == 'trung_hoc' ? 'selected' : '' }}>Trung học</option>
                                        <option value="cao_dang" {{ old('trinh_do_hoc_van', $hocVien->trinh_do_hoc_van) == 'cao_dang' ? 'selected' : '' }}>Cao đẳng</option>
                                        <option value="dai_hoc" {{ old('trinh_do_hoc_van', $hocVien->trinh_do_hoc_van) == 'dai_hoc' ? 'selected' : '' }}>Đại học</option>
                                        <option value="sau_dai_hoc" {{ old('trinh_do_hoc_van', $hocVien->trinh_do_hoc_van) == 'sau_dai_hoc' ? 'selected' : '' }}>Sau đại học</option>
                                    </select>
                                    @error('trinh_do_hoc_van')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Thông tin giáo viên (hiển thị nếu là giáo viên) -->
                    @if(isset($giaoVien))
                        <div id="giao_vien_info" class="col-span-2 bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin giáo viên</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="bang_cap" class="block text-sm font-medium text-gray-700">Bằng cấp</label>
                                    <select name="bang_cap" id="bang_cap" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">-- Chọn bằng cấp --</option>
                                        <option value="dai_hoc" {{ old('bang_cap', $giaoVien->bang_cap) == 'dai_hoc' ? 'selected' : '' }}>Đại học</option>
                                        <option value="thac_si" {{ old('bang_cap', $giaoVien->bang_cap) == 'thac_si' ? 'selected' : '' }}>Thạc sĩ</option>
                                        <option value="tien_si" {{ old('bang_cap', $giaoVien->bang_cap) == 'tien_si' ? 'selected' : '' }}>Tiến sĩ</option>
                                        <option value="khac" {{ old('bang_cap', $giaoVien->bang_cap) == 'khac' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('bang_cap')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="so_nam_kinh_nghiem" class="block text-sm font-medium text-gray-700">Số năm kinh nghiệm</label>
                                    <input type="number" name="so_nam_kinh_nghiem" id="so_nam_kinh_nghiem" value="{{ old('so_nam_kinh_nghiem', $giaoVien->so_nam_kinh_nghiem) }}" min="0" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('so_nam_kinh_nghiem')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="chuyen_mon" class="block text-sm font-medium text-gray-700 mb-2">Chuyên môn</label>
                                    @php
                                        $chuyenMonList = explode(',', $giaoVien->chuyen_mon);
                                    @endphp
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk1" id="chuyen_mon_hsk1" value="hsk1" {{ in_array('hsk1', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk1" class="ml-2 block text-sm text-gray-700">HSK 1</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk2" id="chuyen_mon_hsk2" value="hsk2" {{ in_array('hsk2', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk2" class="ml-2 block text-sm text-gray-700">HSK 2</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk3" id="chuyen_mon_hsk3" value="hsk3" {{ in_array('hsk3', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk3" class="ml-2 block text-sm text-gray-700">HSK 3</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk4" id="chuyen_mon_hsk4" value="hsk4" {{ in_array('hsk4', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk4" class="ml-2 block text-sm text-gray-700">HSK 4</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk5" id="chuyen_mon_hsk5" value="hsk5" {{ in_array('hsk5', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk5" class="ml-2 block text-sm text-gray-700">HSK 5</label>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="chuyen_mon" id="chuyen_mon" value="{{ $giaoVien->chuyen_mon }}">
                                    
                                    @error('chuyen_mon')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Thông tin trợ giảng (hiển thị nếu là trợ giảng) -->
                    @if(isset($troGiang))
                        <div id="tro_giang_info" class="hidden col-span-2 bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin trợ g2iảng</h4>
                            
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
                                    <label for="chuyen_mon" class="block text-sm font-medium text-gray-700 mb-2">Chuyên môn</label>
                                    @php
                                        $chuyenMonList = explode(',', $troGiang->chuyen_mon);
                                    @endphp
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk1" id="chuyen_mon_hsk1" value="hsk1" {{ in_array('hsk1', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk1" class="ml-2 block text-sm text-gray-700">HSK 1</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk2" id="chuyen_mon_hsk2" value="hsk2" {{ in_array('hsk2', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk2" class="ml-2 block text-sm text-gray-700">HSK 2</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk3" id="chuyen_mon_hsk3" value="hsk3" {{ in_array('hsk3', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk3" class="ml-2 block text-sm text-gray-700">HSK 3</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk4" id="chuyen_mon_hsk4" value="hsk4" {{ in_array('hsk4', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk4" class="ml-2 block text-sm text-gray-700">HSK 4</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="chuyen_mon_hsk5" id="chuyen_mon_hsk5" value="hsk5" {{ in_array('hsk5', $chuyenMonList) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            <label for="chuyen_mon_hsk5" class="ml-2 block text-sm text-gray-700">HSK 5</label>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="chuyen_mon" id="chuyen_mon" value="{{ $troGiang->chuyen_mon }}">
                                    
                                    @error('chuyen_mon')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-center mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Hàm hiển thị form tương ứng với vai trò
        function toggleRoleFields() {
            const selectElement = document.getElementById('vai_tro_ids');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const roleName = selectedOption.getAttribute('data-role-name');
            
            // Ẩn tất cả các form thông tin vai trò
            document.getElementById('hoc_vien_info')?.classList.add('hidden');
            document.getElementById('giao_vien_info')?.classList.add('hidden');
            document.getElementById('tro_giang_info')?.classList.add('hidden');
            
            // Hiển thị form tương ứng
            if (roleName === 'hoc_vien') {
                document.getElementById('hoc_vien_info')?.classList.remove('hidden');
            } else if (roleName === 'giao_vien') {
                document.getElementById('giao_vien_info')?.classList.remove('hidden');
            } else if (roleName === 'tro_giang') {
                document.getElementById('tro_giang_info')?.classList.remove('hidden');
            }
        }
        
        // Gọi hàm khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleFields();
            
            // Cập nhật chuyên môn khi checkbox thay đổi
            const chuyenMonCheckboxes = document.querySelectorAll('[name^="chuyen_mon_"]');
            chuyenMonCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateChuyenMon);
            });
        });
        
        // Cập nhật giá trị chuyên môn từ các checkbox
        function updateChuyenMon() {
            const chuyenMonValues = [];
            const chuyenMonCheckboxes = document.querySelectorAll('[name^="chuyen_mon_"]:checked');
            
            chuyenMonCheckboxes.forEach(function(checkbox) {
                chuyenMonValues.push(checkbox.value);
            });
            
            document.getElementById('chuyen_mon').value = chuyenMonValues.join(',');
        }
    </script>
    @endpush
@endsection 