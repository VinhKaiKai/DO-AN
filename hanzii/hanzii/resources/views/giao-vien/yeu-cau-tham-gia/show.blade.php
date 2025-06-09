@extends('layouts.dashboard')

@section('title', 'Chi tiết yêu cầu tham gia')
@section('page-heading', 'Chi tiết yêu cầu tham gia')

@php
    $active = 'yeu-cau-tham-gia';
    $role = 'giao-vien';
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Yêu cầu tham gia #{{ $yeuCau->id }}</h2>
        <a href="{{ route('giao-vien.yeu-cau-tham-gia.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Thông tin yêu cầu</h3>
            
            <div>
                @if($yeuCau->trang_thai == 'cho_xac_nhan')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i> Chờ duyệt
                    </span>
                @elseif($yeuCau->trang_thai == 'da_duyet')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Đã duyệt
                    </span>
                @elseif($yeuCau->trang_thai == 'tu_choi')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i> Từ chối
                    </span>
                @endif
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin cơ bản</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Ngày gửi yêu cầu:</span> 
                            {{ \Carbon\Carbon::parse($yeuCau->ngay_gui)->format('d/m/Y H:i') }}
                        </p>
                        
                        @if($yeuCau->ngay_duyet)
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Ngày phản hồi:</span> 
                            {{ \Carbon\Carbon::parse($yeuCau->ngay_duyet)->format('d/m/Y H:i') }}
                        </p>
                        @endif
                        
                        @if($yeuCau->nguoi_duyet_id)
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Người duyệt:</span> 
                            {{ $yeuCau->nguoiDuyet->ho_ten ?? 'Không xác định' }}
                        </p>
                        @endif
                        
                        @if($yeuCau->ghi_chu)
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Ghi chú từ học viên:</span> 
                            <span class="block mt-1 p-2 bg-white rounded border border-gray-200">{{ $yeuCau->ghi_chu }}</span>
                        </p>
                        @endif
                        
                        @if($yeuCau->ly_do_tu_choi)
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Lý do từ chối:</span> 
                            <span class="block mt-1 p-2 bg-white rounded border border-gray-200 text-red-600">{{ $yeuCau->ly_do_tu_choi }}</span>
                        </p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin học viên</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                @if($yeuCau->hocVien->nguoiDung->avatar)
                                    <img src="{{ Storage::url($yeuCau->hocVien->nguoiDung->avatar) }}" alt="{{ $yeuCau->hocVien->nguoiDung->ho_ten }}" class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium">{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $yeuCau->hocVien->id }}</p>
                            </div>
                        </div>
                        
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Email:</span> 
                            {{ $yeuCau->hocVien->nguoiDung->email }}
                        </p>
                        
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Số điện thoại:</span> 
                            {{ $yeuCau->hocVien->nguoiDung->so_dien_thoai ?? 'Chưa cập nhật' }}
                        </p>
                        
                        <p class="mb-2 text-sm">
                            <span class="font-medium">Ngày đăng ký:</span> 
                            {{ $yeuCau->hocVien->nguoiDung->created_at ? \Carbon\Carbon::parse($yeuCau->hocVien->nguoiDung->created_at)->format('d/m/Y') : 'Không xác định' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Thông tin lớp học</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Mã lớp:</span> 
                                {{ $yeuCau->lopHoc->ma_lop }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Tên lớp:</span> 
                                {{ $yeuCau->lopHoc->ten ?? $yeuCau->lopHoc->ma_lop }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Khóa học:</span> 
                                {{ $yeuCau->lopHoc->khoaHoc->ten ?? 'Không xác định' }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Trạng thái lớp:</span> 
                                @if($yeuCau->lopHoc->trang_thai == 'sap_khai_giang')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sắp khai giảng</span>
                                @elseif($yeuCau->lopHoc->trang_thai == 'dang_dien_ra')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Đang diễn ra</span>
                                @elseif($yeuCau->lopHoc->trang_thai == 'da_ket_thuc')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Đã kết thúc</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ $yeuCau->lopHoc->trang_thai }}</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Giáo viên:</span> 
                                {{ $yeuCau->lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Ngày bắt đầu:</span> 
                                {{ $yeuCau->lopHoc->ngay_bat_dau ? \Carbon\Carbon::parse($yeuCau->lopHoc->ngay_bat_dau)->format('d/m/Y') : 'Chưa xác định' }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Ngày kết thúc:</span> 
                                {{ $yeuCau->lopHoc->ngay_ket_thuc ? \Carbon\Carbon::parse($yeuCau->lopHoc->ngay_ket_thuc)->format('d/m/Y') : 'Chưa xác định' }}
                            </p>
                            
                            <p class="mb-2 text-sm">
                                <span class="font-medium">Số học viên:</span> 
                                {{ $yeuCau->lopHoc->dangKyHocs->count() }} / {{ $yeuCau->lopHoc->so_luong_toi_da ?? 'Không giới hạn' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($yeuCau->trang_thai == 'cho_xac_nhan')
    <div class="flex space-x-4 mb-6">
        <button type="button" onclick="document.getElementById('modal-duyet').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-check mr-2"></i> Duyệt yêu cầu
        </button>
        
        <button type="button" onclick="document.getElementById('modal-tu-choi').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-times mr-2"></i> Từ chối yêu cầu
        </button>
    </div>
    
    <!-- Modal Duyệt -->
    <div id="modal-duyet" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('giao-vien.yeu-cau-tham-gia.duyet', $yeuCau->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Duyệt yêu cầu tham gia
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Bạn có chắc chắn muốn duyệt yêu cầu tham gia của học viên <span class="font-semibold">{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</span> vào lớp <span class="font-semibold">{{ $yeuCau->lopHoc->ma_lop }}</span>? Học viên sẽ được thêm vào lớp học.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Xác nhận duyệt
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('modal-duyet').classList.add('hidden')">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Từ chối -->
    <div id="modal-tu-choi" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('giao-vien.yeu-cau-tham-gia.tu-choi', $yeuCau->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-times text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Từ chối yêu cầu tham gia
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Bạn đang từ chối yêu cầu tham gia của học viên <span class="font-semibold">{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</span> vào lớp <span class="font-semibold">{{ $yeuCau->lopHoc->ma_lop }}</span>. Vui lòng cung cấp lý do từ chối.
                                    </p>
                                    <div>
                                        <label for="ly_do_tu_choi" class="block text-sm font-medium text-gray-700">Lý do từ chối <span class="text-red-500">*</span></label>
                                        <textarea id="ly_do_tu_choi" name="ly_do_tu_choi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Nhập lý do từ chối yêu cầu..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Xác nhận từ chối
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('modal-tu-choi').classList.add('hidden')">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 