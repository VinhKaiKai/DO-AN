@extends('layouts.dashboard')

@section('title', 'Chi tiết người dùng')
@section('page-heading', 'Chi tiết người dùng')

@php
    $active = 'nguoi-dung';
    $role = 'admin';
@endphp

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Thông tin người dùng</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.nguoi-dung.edit', $nguoiDung->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.nguoi-dung.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Thông tin cá nhân -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin cá nhân</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Họ và tên:</h5>
                            <p class="text-base font-medium">{{ $nguoiDung->ho }} {{ $nguoiDung->ten }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Email:</h5>
                            <p class="text-base">{{ $nguoiDung->email }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Số điện thoại:</h5>
                            <p class="text-base">{{ $nguoiDung->so_dien_thoai }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Địa chỉ:</h5>
                            <p class="text-base">{{ $nguoiDung->dia_chi ?? 'Chưa có thông tin' }}</p>
                        </div>
                   
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Vai trò</h3>
                            <div class="bg-white p-4 rounded-lg shadow">
                                @if($nguoiDung->vaiTro)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold mr-2">
                                        {{ $nguoiDung->vaiTro->ten }}
                                    </span>
                                @else
                                    <p class="text-gray-500 italic">Chưa có vai trò</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin học viên -->
            @if($nguoiDung->loai_tai_khoan == 'hoc_vien' && isset($hocVien))
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin học viên</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Ngày sinh:</h5>
                                <p class="text-base">{{ $hocVien->ngay_sinh ? \Carbon\Carbon::parse($hocVien->ngay_sinh)->format('d/m/Y') : 'Chưa có thông tin' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Trình độ học vấn:</h5>
                                <p class="text-base">
                                    @switch($hocVien->trinh_do_hoc_van)
                                        @case('trung_hoc')
                                            Trung học
                                            @break
                                        @case('cao_dang')
                                            Cao đẳng
                                            @break
                                        @case('dai_hoc')
                                            Đại học
                                            @break
                                        @case('sau_dai_hoc')
                                            Sau đại học
                                            @break
                                        @default
                                            {{ $hocVien->trinh_do_hoc_van ?? 'Chưa có thông tin' }}
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Trạng thái:</h5>
                                <p class="text-base">
                                    @switch($hocVien->trang_thai)
                                        @case('hoat_dong')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Hoạt động</span>
                                            @break
                                        @case('tam_ngung')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Tạm ngưng</span>
                                            @break
                                        @case('da_nghi')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Đã nghỉ</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ $hocVien->trang_thai }}</span>
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Thông tin giáo viên -->
            @if(isset($giaoVien))
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin giáo viên</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Bằng cấp:</h5>
                                <p class="text-base">
                                    @switch($giaoVien->bang_cap)
                                        @case('dai_hoc')
                                            Đại học
                                            @break
                                        @case('thac_si')
                                            Thạc sĩ
                                            @break
                                        @case('tien_si')
                                            Tiến sĩ
                                            @break
                                        @default
                                            {{ $giaoVien->bang_cap ?? 'Chưa có thông tin' }}
                                    @endswitch
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Số năm kinh nghiệm:</h5>
                                <p class="text-base">{{ $giaoVien->so_nam_kinh_nghiem }} năm</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Chuyên môn:</h5>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @php
                                        $chuyenMonList = explode(',', $giaoVien->chuyen_mon);
                                    @endphp
                                    
                                    @foreach($chuyenMonList as $chuyenMon)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ strtoupper($chuyenMon) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Thông tin trợ giảng -->
            @if(isset($troGiang))
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin trợ giảng</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Bằng cấp:</h5>
                                <p class="text-base">
                                    @switch($troGiang->bang_cap)
                                        @case('dai_hoc')
                                            Đại học
                                            @break
                                        @case('thac_si')
                                            Thạc sĩ
                                            @break
                                        @case('tien_si')
                                            Tiến sĩ
                                            @break
                                        @default
                                            {{ $troGiang->bang_cap ?? 'Chưa có thông tin' }}
                                    @endswitch
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Số năm kinh nghiệm:</h5>
                                <p class="text-base">{{ $troGiang->so_nam_kinh_nghiem }} năm</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-500">Chuyên môn:</h5>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @php
                                        $chuyenMonList = explode(',', $troGiang->chuyen_mon);
                                    @endphp
                                    
                                    @foreach($chuyenMonList as $chuyenMon)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ strtoupper($chuyenMon) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Thông tin khác -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-700 mb-4">Thông tin khác</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Ngày tạo:</h5>
                            <p class="text-base">{{ $nguoiDung->tao_luc ? \Carbon\Carbon::parse($nguoiDung->tao_luc)->format('d/m/Y H:i:s') : 'Chưa có thông tin' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-500">Cập nhật lần cuối:</h5>
                            <p class="text-base">{{ $nguoiDung->cap_nhat_luc ? \Carbon\Carbon::parse($nguoiDung->cap_nhat_luc)->format('d/m/Y H:i:s') : 'Chưa có thông tin' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nút hành động -->
            <div class="mt-6 flex justify-between">
                <form action="{{ route('admin.nguoi-dung.destroy', $nguoiDung->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-trash-alt mr-2"></i> Xóa người dùng
                    </button>
                </form>
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.nguoi-dung.edit', $nguoiDung->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection 