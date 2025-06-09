@extends('layouts.dashboard')

@section('title', 'Chi tiết thanh toán lương')
@section('page-heading', 'Chi tiết thanh toán lương')

@php
    $active = 'thanh-toan-luong';
    $role = 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Thông tin lớp học -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h5 class="text-lg font-medium text-gray-900">
                Thông tin lớp: {{ $lopHoc->ten }}
                <span class="text-sm text-gray-500 font-normal">({{ $lopHoc->ma_lop }})</span>
            </h5>
        </div>
        
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Khóa học: <span class="font-medium text-gray-800">{{ $lopHoc->khoaHoc->ten }}</span></p>
                    <p class="text-sm text-gray-600 mb-1">Giáo viên: <span class="font-medium text-gray-800">{{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa có' }}</span></p>
                    <p class="text-sm text-gray-600 mb-1">Trợ giảng: <span class="font-medium text-gray-800">{{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa có' }}</span></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ngày bắt đầu: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</span></p>
                    <p class="text-sm text-gray-600 mb-1">Ngày kết thúc: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</span></p>
                    <p class="text-sm text-gray-600 mb-1">Tổng số học viên: <span class="font-medium text-gray-800">{{ $lopHoc->hocViens->count() }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thanh toán lương -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h5 class="text-lg font-medium text-gray-900">Thanh toán lương</h5>
        </div>
        
        <div class="p-4">
            <div class="mb-6">
                <h6 class="text-base font-medium text-gray-800 mb-3">Tổng tiền thu</h6>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-lg font-semibold text-red-600">{{ number_format($thanhToanLuong->tong_tien_thu, 0, ',', '.') }} đồng</p>
                </div>
            </div>
            
            <form action="{{ route('admin.thanh-toan-luong.update', $thanhToanLuong->id) }}" method="POST" class="mb-6">
                @csrf
                @method('PUT')
                
                <h6 class="text-base font-medium text-gray-800 mb-3">Nhập hệ số lương</h6>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="he_so_luong_giao_vien" class="block text-sm font-medium text-gray-700 mb-1">Hệ số lương giáo viên (%)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="number" name="he_so_luong_giao_vien" id="he_so_luong_giao_vien" value="{{ $thanhToanLuong->he_so_luong_giao_vien }}" min="0" max="100" class="border-gray-300 focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm rounded-md">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Số tiền: <span class="font-medium">{{ number_format(($thanhToanLuong->tong_tien_thu * $thanhToanLuong->he_so_luong_giao_vien / 100), 0, ',', '.') }} đồng</span></p>
                    </div>
                    
                    <div>
                        <label for="he_so_luong_tro_giang" class="block text-sm font-medium text-gray-700 mb-1">Hệ số lương trợ giảng (%)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="number" name="he_so_luong_tro_giang" id="he_so_luong_tro_giang" value="{{ $thanhToanLuong->he_so_luong_tro_giang }}" min="0" max="100" class="border-gray-300 focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm rounded-md">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Số tiền: <span class="font-medium">{{ number_format(($thanhToanLuong->tong_tien_thu * $thanhToanLuong->he_so_luong_tro_giang / 100), 0, ',', '.') }} đồng</span></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Lưu hệ số lương
                    </button>
                </div>
            </form>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <!-- Trạng thái thanh toán giáo viên -->
                <div class="border rounded-md overflow-hidden">
                    <div class="bg-gray-50 p-3 border-b">
                        <h6 class="font-medium">Thanh toán lương giáo viên</h6>
                    </div>
                    <div class="p-4">
                        <p class="text-sm mb-3">
                            <span class="font-medium">Giáo viên:</span> {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa có' }}
                        </p>
                        <p class="text-sm mb-3">
                            <span class="font-medium">Số tiền:</span> {{ number_format($thanhToanLuong->tien_luong_giao_vien, 0, ',', '.') }} đồng
                        </p>
                        <p class="text-sm mb-4">
                            <span class="font-medium">Trạng thái:</span>
                            @if($thanhToanLuong->trang_thai_giao_vien === 'da_thanh_toan')
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Đã trả lương</span>
                            @else
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Chưa trả lương</span>
                            @endif
                        </p>
                        
                        <form action="{{ route('admin.thanh-toan-luong.update-giao-vien-status', $thanhToanLuong->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái thanh toán</label>
                                <div class="mt-2 space-x-2">
                                    <div class="flex items-center">
                                        <input type="radio" name="trang_thai_giao_vien" id="gv_chua_thanh_toan" value="chua_thanh_toan" {{ $thanhToanLuong->trang_thai_giao_vien === 'chua_thanh_toan' ? 'checked' : '' }} class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                        <label for="gv_chua_thanh_toan" class="ml-2 block text-sm font-medium text-gray-700">Chưa trả lương</label>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <input type="radio" name="trang_thai_giao_vien" id="gv_da_thanh_toan" value="da_thanh_toan" {{ $thanhToanLuong->trang_thai_giao_vien === 'da_thanh_toan' ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                        <label for="gv_da_thanh_toan" class="ml-2 block text-sm font-medium text-gray-700">Đã trả lương</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-check mr-2"></i> Cập nhật trạng thái
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Trạng thái thanh toán trợ giảng -->
                <div class="border rounded-md overflow-hidden">
                    <div class="bg-gray-50 p-3 border-b">
                        <h6 class="font-medium">Thanh toán lương trợ giảng</h6>
                    </div>
                    <div class="p-4">
                        <p class="text-sm mb-3">
                            <span class="font-medium">Trợ giảng:</span> {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa có' }}
                        </p>
                        <p class="text-sm mb-3">
                            <span class="font-medium">Số tiền:</span> {{ number_format($thanhToanLuong->tien_luong_tro_giang, 0, ',', '.') }} đồng
                        </p>
                        <p class="text-sm mb-4">
                            <span class="font-medium">Trạng thái:</span>
                            @if($thanhToanLuong->trang_thai_tro_giang === 'da_thanh_toan')
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Đã trả lương</span>
                            @else
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Chưa trả lương</span>
                            @endif
                        </p>
                        
                        <form action="{{ route('admin.thanh-toan-luong.update-tro-giang-status', $thanhToanLuong->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái thanh toán</label>
                                <div class="mt-2 space-x-2">
                                    <div class="flex items-center">
                                        <input type="radio" name="trang_thai_tro_giang" id="tg_chua_thanh_toan" value="chua_thanh_toan" {{ $thanhToanLuong->trang_thai_tro_giang === 'chua_thanh_toan' ? 'checked' : '' }} class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                        <label for="tg_chua_thanh_toan" class="ml-2 block text-sm font-medium text-gray-700">Chưa trả lương</label>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <input type="radio" name="trang_thai_tro_giang" id="tg_da_thanh_toan" value="da_thanh_toan" {{ $thanhToanLuong->trang_thai_tro_giang === 'da_thanh_toan' ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                        <label for="tg_da_thanh_toan" class="ml-2 block text-sm font-medium text-gray-700">Đã trả lương</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-check mr-2"></i> Cập nhật trạng thái
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Button hoàn thành thanh toán lương -->
            <div class="mt-8 flex justify-center">
                <form action="{{ route('admin.thanh-toan-luong.complete', $thanhToanLuong->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 {{ ($thanhToanLuong->trang_thai_giao_vien !== 'da_thanh_toan' || $thanhToanLuong->trang_thai_tro_giang !== 'da_thanh_toan') ? 'opacity-50 cursor-not-allowed' : '' }}" {{ ($thanhToanLuong->trang_thai_giao_vien !== 'da_thanh_toan' || $thanhToanLuong->trang_thai_tro_giang !== 'da_thanh_toan') ? 'disabled' : '' }}>
                        <i class="fas fa-check-circle mr-2"></i> Thanh toán lương hoàn thành
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="flex justify-between">
        <a href="{{ route('admin.thanh-toan-luong.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const heSoLuongGiaoVien = document.getElementById('he_so_luong_giao_vien');
        const heSoLuongTroGiang = document.getElementById('he_so_luong_tro_giang');
        const tongTienThu = {{ $thanhToanLuong->tong_tien_thu }};
        
        function tinhLuong() {
            const heSoGV = parseFloat(heSoLuongGiaoVien.value) || 0;
            const heSoTG = parseFloat(heSoLuongTroGiang.value) || 0;
            
            const luongGV = (tongTienThu * heSoGV) / 100;
            const luongTG = (tongTienThu * heSoTG) / 100;
            
            const formatterVND = new Intl.NumberFormat('vi-VN');
            
            heSoLuongGiaoVien.parentElement.nextElementSibling.innerHTML = 
                `Số tiền: <span class="font-medium">${formatterVND.format(luongGV)} đồng</span>`;
            
            heSoLuongTroGiang.parentElement.nextElementSibling.innerHTML = 
                `Số tiền: <span class="font-medium">${formatterVND.format(luongTG)} đồng</span>`;
        }
        
        heSoLuongGiaoVien.addEventListener('input', tinhLuong);
        heSoLuongTroGiang.addEventListener('input', tinhLuong);
    });
</script>
@endsection 