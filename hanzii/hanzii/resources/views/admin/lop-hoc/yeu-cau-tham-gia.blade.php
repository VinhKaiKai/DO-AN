@extends('layouts.dashboard')

@section('title', 'Chi tiết lớp học')
@section('page-heading', 'Chi tiết lớp học')

@php
    $active = 'lop-hoc';
    $role = 'admin';
@endphp

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Yêu cầu tham gia lớp học: {{ $lopHoc->ten }}</h1>
            <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại chi tiết lớp học
            </a>
        </div>
        
        <div class="flex flex-wrap gap-4 mt-3 text-sm">
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                Mã lớp: {{ $lopHoc->ma_lop }}
            </div>
            
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                Khóa học: {{ $lopHoc->khoaHoc->ten ?? 'Chưa có' }}
            </div>
            
            <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                Giáo viên: {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}
            </div>
            
            <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                Số học viên: {{ $lopHoc->hocViens->count() }} / {{ $lopHoc->so_luong_toi_da }}
            </div>
        </div>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Danh sách yêu cầu tham gia
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Danh sách học viên đang yêu cầu tham gia lớp học này
            </p>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        @if($yeuCauThamGia->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($yeuCauThamGia as $yeuCau)
                    <li class="p-4">
                        <div class="flex items-center justify-between flex-wrap">
                            <div class="mb-2 md:mb-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($yeuCau->hocVien && $yeuCau->hocVien->nguoiDung && $yeuCau->hocVien->nguoiDung->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $yeuCau->hocVien->nguoiDung->avatar) }}" alt="{{ $yeuCau->hocVien->nguoiDung->ho_ten ?? 'Học viên' }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $yeuCau->hocVien->nguoiDung->ho_ten ?? 'Không xác định' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $yeuCau->hocVien->nguoiDung->email ?? 'Không có email' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center">
                                <div class="mr-4 mb-2 sm:mb-0 flex flex-col">
                                    <span class="text-xs text-gray-500">Ngày gửi:</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($yeuCau->ngay_gui)->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="mr-4 mb-3 sm:mb-0">
                                    @switch($yeuCau->trang_thai)
                                        @case('cho_xac_nhan')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                Chờ duyệt
                                            </span>
                                            @break
                                        @case('da_duyet')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                Đã duyệt
                                            </span>
                                            @break
                                        @case('bi_tu_choi')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                Đã từ chối
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                                {{ $yeuCau->trang_thai }}
                                            </span>
                                    @endswitch
                                </div>
                                
                                @if($yeuCau->trang_thai == 'cho_xac_nhan')
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.lop-hoc.duyet-yeu-cau', ['id' => $lopHoc->id, 'yeuCauId' => $yeuCau->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <i class="fas fa-check mr-1"></i> Duyệt
                                            </button>
                                        </form>
                                        
                                        <button type="button" onclick="openTuChoiModal('{{ $yeuCau->id }}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-times mr-1"></i> Từ chối
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($yeuCau->ghi_chu)
                            <div class="mt-3 bg-gray-50 p-3 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900">Ghi chú của học viên:</h4>
                                <p class="text-sm text-gray-700">{{ $yeuCau->ghi_chu }}</p>
                            </div>
                        @endif
                        
                        @if($yeuCau->trang_thai == 'bi_tu_choi' && $yeuCau->ly_do_tu_choi)
                            <div class="mt-3 bg-red-50 p-3 rounded-md">
                                <h4 class="text-sm font-medium text-red-800">Lý do từ chối:</h4>
                                <p class="text-sm text-red-700">{{ $yeuCau->ly_do_tu_choi }}</p>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                {{ $yeuCauThamGia->links() }}
            </div>
        @else
            <div class="px-4 py-5 sm:p-6 text-center">
                <p class="text-gray-500">Không có yêu cầu tham gia nào</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal từ chối -->
<div id="tuChoiModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="tuChoiForm" action="" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Từ chối yêu cầu tham gia
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Vui lòng cung cấp lý do từ chối yêu cầu tham gia lớp học này.
                                </p>
                                
                                <div class="mt-3">
                                    <label for="ly_do_tu_choi" class="block text-sm font-medium text-gray-700">Lý do từ chối <span class="text-red-500">*</span></label>
                                    <textarea id="ly_do_tu_choi" name="ly_do_tu_choi" rows="3" class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Từ chối
                    </button>
                    <button type="button" onclick="closeTuChoiModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openTuChoiModal(yeuCauId) {
        const modal = document.getElementById('tuChoiModal');
        const form = document.getElementById('tuChoiForm');
        
        form.action = '{{ route('admin.lop-hoc.tu-choi-yeu-cau', ['id' => $lopHoc->id, 'yeuCauId' => '__PLACEHOLDER__']) }}'.replace('__PLACEHOLDER__', yeuCauId);
        modal.classList.remove('hidden');
    }
    
    function closeTuChoiModal() {
        const modal = document.getElementById('tuChoiModal');
        modal.classList.add('hidden');
    }
    
    // Đóng modal khi người dùng nhấp vào bên ngoài
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('tuChoiModal');
        if (event.target === modal) {
            closeTuChoiModal();
        }
    });
</script>
@endsection 