@extends('layouts.dashboard')

@section('title', 'Quản lý yêu cầu tham gia')
@section('page-heading', 'Quản lý yêu cầu tham gia')

@php
    $active = 'yeu-cau-tham-gia';
    $role = 'giao-vien';
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Thống kê yêu cầu -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tổng số yêu cầu</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($tongYeuCau) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Chờ duyệt</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($yeuCauChoDuyet) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Đã duyệt</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($yeuCauDaDuyet) }}</p>
                </div>
                <div class="bg-green-100 rounded-full h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Từ chối</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($yeuCauTuChoi) }}</p>
                </div>
                <div class="bg-red-100 rounded-full h-12 w-12 flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lọc -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('giao-vien.yeu-cau-tham-gia.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <option value="all" {{ $trangThai == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="cho_xac_nhan" {{ $trangThai == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="da_duyet" {{ $trangThai == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="tu_choi" {{ $trangThai == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-filter mr-2"></i> Lọc
                </button>
                <a href="{{ route('giao-vien.yeu-cau-tham-gia.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-sync-alt mr-2"></i> Đặt lại
                </a>
            </div>
        </form>
    </div>
    
    <!-- Danh sách yêu cầu tham gia -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Danh sách yêu cầu tham gia</h2>
        </div>
        
        @if($yeuCaus->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học viên</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp học</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày gửi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($yeuCaus as $yeuCau)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $yeuCau->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $yeuCau->hocVien->nguoiDung->ho_ten }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $yeuCau->hocVien->nguoiDung->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $yeuCau->lopHoc->ma_lop }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $yeuCau->lopHoc->ten }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($yeuCau->ngay_gui)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($yeuCau->trang_thai == 'cho_xac_nhan')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Chờ duyệt
                                        </span>
                                    @elseif($yeuCau->trang_thai == 'da_duyet')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã duyệt
                                        </span>
                                    @elseif($yeuCau->trang_thai == 'tu_choi')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Từ chối
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('giao-vien.yeu-cau-tham-gia.show', $yeuCau->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($yeuCau->trang_thai == 'cho_xac_nhan')
                                            <button type="button" onclick="document.getElementById('modal-duyet-{{ $yeuCau->id }}').classList.remove('hidden')" class="text-green-600 hover:text-green-900" title="Duyệt yêu cầu">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <button type="button" onclick="document.getElementById('modal-tu-choi-{{ $yeuCau->id }}').classList.remove('hidden')" class="text-red-600 hover:text-red-900" title="Từ chối yêu cầu">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Modal Duyệt -->
                            <div id="modal-duyet-{{ $yeuCau->id }}" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('modal-duyet-{{ $yeuCau->id }}').classList.add('hidden')">
                                                    Hủy
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Từ chối -->
                            <div id="modal-tu-choi-{{ $yeuCau->id }}" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                                                <label for="ly_do_tu_choi_{{ $yeuCau->id }}" class="block text-sm font-medium text-gray-700">Lý do từ chối <span class="text-red-500">*</span></label>
                                                                <textarea id="ly_do_tu_choi_{{ $yeuCau->id }}" name="ly_do_tu_choi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Nhập lý do từ chối yêu cầu..." required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Xác nhận từ chối
                                                </button>
                                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('modal-tu-choi-{{ $yeuCau->id }}').classList.add('hidden')">
                                                    Hủy
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $yeuCaus->withQueryString()->links() }}
            </div>
        @else
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không có yêu cầu nào</h3>
                <p class="mt-1 text-sm text-gray-500">Chưa có yêu cầu tham gia lớp học nào.</p>
            </div>
        @endif
    </div>
</div>
@endsection 