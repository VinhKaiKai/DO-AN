@extends('layouts.dashboard')

@section('title', 'Thêm người dùng mới')
@section('page-heading', 'Thêm người dùng mới')

@php
    $active = 'nguoi-dung';
    $role = 'admin';
@endphp

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Thêm người dùng mới</h3>
                <a href="{{ route('admin.nguoi-dung.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.nguoi-dung.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin cá nhân -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin cá nhân</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="ho" class="block text-sm font-medium text-gray-700">Họ</label>
                                <input type="text" name="ho" id="ho" value="{{ old('ho') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ho')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="ten" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" name="ten" id="ten" value="{{ old('ten') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ten')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="so_dien_thoai" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="{{ old('so_dien_thoai') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('so_dien_thoai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('password')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="dia_chi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                <input type="text" name="dia_chi" id="dia_chi" value="{{ old('dia_chi') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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
                                @foreach(\App\Models\VaiTro::all() as $vaiTro)
                                    <option value="{{ $vaiTro->id }}" data-role-name="{{ $vaiTro->ten }}">
                                        {{ $vaiTro->ten }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i> Lưu ý: Loại tài khoản sẽ được tự động xác định dựa trên vai trò
                            </p>
                            @error('vai_tro_ids')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Thông tin học viên (hiển thị/ẩn dựa vào loại tài khoản) -->
                    <div id="hoc_vien_info" class="col-span-2 bg-gray-50 p-4 rounded-lg" style="display: none;">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin học viên</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="ngay_sinh" class="block text-sm font-medium text-gray-700">Ngày sinh</label>
                                <input type="date" name="ngay_sinh" id="ngay_sinh" value="{{ old('ngay_sinh') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('ngay_sinh')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="trinh_do_hoc_van" class="block text-sm font-medium text-gray-700">Trình độ học vấn</label>
                                <select name="trinh_do_hoc_van" id="trinh_do_hoc_van" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">-- Chọn trình độ --</option>
                                    <option value="trung_hoc" {{ old('trinh_do_hoc_van') == 'trung_hoc' ? 'selected' : '' }}>Trung học</option>
                                    <option value="cao_dang" {{ old('trinh_do_hoc_van') == 'cao_dang' ? 'selected' : '' }}>Cao đẳng</option>
                                    <option value="dai_hoc" {{ old('trinh_do_hoc_van') == 'dai_hoc' ? 'selected' : '' }}>Đại học</option>
                                    <option value="sau_dai_hoc" {{ old('trinh_do_hoc_van') == 'sau_dai_hoc' ? 'selected' : '' }}>Sau đại học</option>
                                </select>
                                @error('trinh_do_hoc_van')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin giáo viên/trợ giảng (hiển thị/ẩn dựa vào loại tài khoản) -->
                    <div id="giao_vien_tro_giang_info" class="col-span-2 bg-gray-50 p-4 rounded-lg" style="display: none;">
                        <h4 class="font-medium text-lg mb-4 text-gray-700">Thông tin chuyên môn</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="bang_cap" class="block text-sm font-medium text-gray-700">Bằng cấp</label>
                                <select name="bang_cap" id="bang_cap" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">-- Chọn bằng cấp --</option>
                                    <option value="dai_hoc" {{ old('bang_cap') == 'dai_hoc' ? 'selected' : '' }}>Đại học</option>
                                    <option value="thac_si" {{ old('bang_cap') == 'thac_si' ? 'selected' : '' }}>Thạc sĩ</option>
                                    <option value="tien_si" {{ old('bang_cap') == 'tien_si' ? 'selected' : '' }}>Tiến sĩ</option>
                                    <option value="khac" {{ old('bang_cap') == 'khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('bang_cap')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="so_nam_kinh_nghiem" class="block text-sm font-medium text-gray-700">Số năm kinh nghiệm</label>
                                <input type="number" name="so_nam_kinh_nghiem" id="so_nam_kinh_nghiem" value="{{ old('so_nam_kinh_nghiem') }}" min="0" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('so_nam_kinh_nghiem')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="chuyen_mon" class="block text-sm font-medium text-gray-700">Chuyên môn</label>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk1" id="chuyen_mon_hsk1" value="hsk1" {{ old('chuyen_mon_hsk1') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk1" class="ml-2 block text-sm text-gray-700">HSK 1</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk2" id="chuyen_mon_hsk2" value="hsk2" {{ old('chuyen_mon_hsk2') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk2" class="ml-2 block text-sm text-gray-700">HSK 2</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk3" id="chuyen_mon_hsk3" value="hsk3" {{ old('chuyen_mon_hsk3') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk3" class="ml-2 block text-sm text-gray-700">HSK 3</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk4" id="chuyen_mon_hsk4" value="hsk4" {{ old('chuyen_mon_hsk4') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk4" class="ml-2 block text-sm text-gray-700">HSK 4</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="chuyen_mon_hsk5" id="chuyen_mon_hsk5" value="hsk5" {{ old('chuyen_mon_hsk5') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <label for="chuyen_mon_hsk5" class="ml-2 block text-sm text-gray-700">HSK 5</label>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="chuyen_mon" id="chuyen_mon" value="{{ old('chuyen_mon') }}">
                                
                                @error('chuyen_mon')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-save mr-2"></i> Thêm người dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hocVienInfo = document.getElementById('hoc_vien_info');
        const giaoVienTroGiangInfo = document.getElementById('giao_vien_tro_giang_info');
        
        // Thiết lập sự kiện cho các checkbox chuyên môn để cập nhật ngay khi chọn/bỏ chọn
        const chuyenMonCheckboxes = document.querySelectorAll('input[id^="chuyen_mon_hsk"]');
        chuyenMonCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateChuyenMon);
        });
        
        // Cập nhật giá trị chuyên môn ban đầu nếu có
        updateChuyenMon();
        
        // Xử lý form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Cập nhật chuyên môn trước khi submit
            updateChuyenMon();
            
            // Submit form
            this.submit();
        });
        
        // Gọi hàm hiển thị form tương ứng với vai trò khi trang được tải
        toggleRoleFields();
    });
    
    // Hàm hiển thị form tương ứng với vai trò được chọn
    function toggleRoleFields() {
        const selectElement = document.getElementById('vai_tro_ids');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const roleName = selectedOption?.getAttribute('data-role-name');
        
        const hocVienInfo = document.getElementById('hoc_vien_info');
        const giaoVienTroGiangInfo = document.getElementById('giao_vien_tro_giang_info');
        
        // Ẩn tất cả các form
        hocVienInfo.style.display = 'none';
        giaoVienTroGiangInfo.style.display = 'none';
        
        // Hiển thị form tương ứng với vai trò
        if (roleName === 'hoc_vien') {
            hocVienInfo.style.display = 'block';
        } else if (roleName === 'giao_vien' || roleName === 'tro_giang') {
            giaoVienTroGiangInfo.style.display = 'block';
        }
    }
    
    // Hàm cập nhật giá trị chuyên môn
    function updateChuyenMon() {
        const chuyenMonCheckboxes = ['chuyen_mon_hsk1', 'chuyen_mon_hsk2', 'chuyen_mon_hsk3', 'chuyen_mon_hsk4', 'chuyen_mon_hsk5'];
        const chuyenMonInput = document.getElementById('chuyen_mon');
        
        const selectedChuyenMon = [];
        chuyenMonCheckboxes.forEach(function(id) {
            if (document.getElementById(id) && document.getElementById(id).checked) {
                selectedChuyenMon.push(document.getElementById(id).value);
            }
        });
        
        // Cập nhật giá trị cho input chuyen_mon
        chuyenMonInput.value = selectedChuyenMon.join(',');
    }
</script>
@endpush 