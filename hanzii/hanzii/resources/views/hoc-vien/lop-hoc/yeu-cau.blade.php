@extends('layouts.dashboard')

@section('title', 'Đăng ký tham gia lớp học')

@section('content')
@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Đăng ký tham gia lớp học</h2>
        <div class="flex space-x-2">
            <a href="{{ route('hoc-vien.lop-hoc.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Quay lại
            </a>
            
            <button onclick="openModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Tìm lớp học
            </button>
        </div>
    </div>
    
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Danh sách đăng ký lớp học</h3>
        
        @forelse($dangKyHoc as $dangKy)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-4">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $dangKy->lopHoc->ten ?? 'Lớp học: ' . $dangKy->lopHoc->ma_lop }}</h4>
                        <p class="text-sm text-gray-500">Khoá học: {{ $dangKy->lopHoc->khoaHoc->ten }}</p>
                        <p class="text-sm text-gray-500">Giáo viên: {{ $dangKy->lopHoc->giaoVien->nguoiDung->ho_ten }}</p>
                    </div>
                    <div>
                        @if($dangKy->trang_thai == 'cho_xac_nhan')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Đang chờ duyệt
                            </span>
                      
                        @elseif($dangKy->trang_thai == 'da_xac_nhan')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đã được chấp nhận
                            </span>
                       
                        @endif
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Ngày đăng ký: {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y H:i') }}
                    </div>
                    
                    @if($dangKy->ghi_chu)
                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-700">Ghi chú:</p>
                            <p class="text-sm text-gray-600">{{ $dangKy->ghi_chu }}</p>
                        </div>
                    @endif
                    
                    @if($dangKy->trang_thai == 'tu_choi' && $dangKy->ly_do_tu_choi)
                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-700">Lý do từ chối:</p>
                            <p class="text-sm text-gray-600">{{ $dangKy->ly_do_tu_choi }}</p>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        @if(in_array($dangKy->trang_thai, ['da_xac_nhan']))
                            <a href="{{ route('hoc-vien.lop-hoc.show', $dangKy->lopHoc->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Xem lớp học
                            </a>
                        @elseif($dangKy->trang_thai == 'cho_xac_nhan')
                            <div class="text-sm text-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Vui lòng chờ giáo viên phê duyệt
                            </div>
                        @elseif($dangKy->trang_thai == 'cho_thanh_toan')
                            <a href="{{ route('hoc-vien.thanh-toan.create', ['dang_ky_id' => $dangKy->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Thanh toán
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có đăng ký lớp học nào</h3>
                <p class="mt-1 text-sm text-gray-500">Bạn chưa đăng ký tham gia lớp học nào.</p>
                <div class="mt-6">
                    <button onclick="openModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Tìm lớp học
                    </button>
                </div>
            </div>
        @endforelse
        
        {{ $dangKyHoc->links() }}
    </div>
</div>

<!-- Modal Tìm lớp học -->
<div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Tìm lớp học</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Nhập mã lớp học để tìm kiếm và đăng ký tham gia lớp học.</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('hoc-vien.lop-hoc.tim-kiem') }}" method="POST" class="mt-5">
                    @csrf
                    <div>
                        <label for="ma_lop" class="block text-sm font-medium text-gray-700">Mã lớp học</label>
                        <input type="text" name="ma_lop" id="ma_lop" required class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Nhập mã lớp học">
                    </div>
                    
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tìm kiếm
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Huỷ bỏ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
@endsection 