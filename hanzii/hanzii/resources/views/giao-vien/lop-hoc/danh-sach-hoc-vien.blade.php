@extends('layouts.dashboard')

@section('title', 'Danh sách học viên lớp ' . $lopHoc->ten)
@section('page-heading', 'Danh sách học viên lớp ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách học viên lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('giao-vien.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông báo -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Thành công!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Lỗi!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Thông tin lớp học -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin lớp học</h3>
                <p class="mt-1 text-sm text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</p>
                <p class="mt-1 text-sm text-gray-900">Hình thức: {{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin giảng dạy</h3>
                <p class="mt-1 text-sm text-gray-900">Giáo viên: {{ $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten }}</p>
                <p class="mt-1 text-sm text-gray-900">Trợ giảng: {{ $lopHoc->troGiang->nguoiDung->ho . ' ' . $lopHoc->troGiang->nguoiDung->ten }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thời gian học</h3>
                <p class="mt-1 text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                <p class="mt-1 text-sm text-gray-900">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thống kê và tính năng -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Tổng số học viên</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $tongSo }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Đã xác nhận</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $daXacNhan }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">Chờ xác nhận</h3>
                        <p class="text-xl font-semibold text-gray-800">{{ $chuaXacNhan }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Đã từ chối</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $daHuy }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Danh sách học viên -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="hocVienTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin liên hệ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày đăng ký
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($tongSo > 0)
                        @php $index = 0; @endphp
                        @foreach($dangKyHocs as $trangThai => $dkGroup)
                            @foreach($dkGroup as $dangKy)
                                @php $index++; @endphp
                                <tr id="hoc-vien-{{ $dangKy->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                                {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ten, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $dangKy->hocVien->nguoiDung->ho . ' ' . $dangKy->hocVien->nguoiDung->ten }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->email }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->so_dien_thoai }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = '';
                                            $statusText = '';
                                            
                                            switch($trangThai) {
                                                case 'cho_xac_nhan':
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                    $statusText = 'Chờ xác nhận';
                                                    break;
                                                case 'da_duyet':
                                                case 'da_xac_nhan':
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                    $statusText = 'Đã xác nhận';
                                                    break;
                                                case 'tu_choi':
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                    $statusText = 'Từ chối';
                                                    break;
                                                case 'da_huy':
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-gray-100 text-gray-800';
                                                    $statusText = ucfirst(str_replace('_', ' ', $trangThai));
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            @if($trangThai == 'cho_xac_nhan')
                                                <!-- Nút xác nhận -->
                                                <form action="{{ route('giao-vien.xac-nhan-hoc-vien', ['id' => $lopHoc->id, 'dangKyId' => $dangKy->id]) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="bg-green-100 text-green-600 p-2 rounded-md hover:bg-green-200" title="Xác nhận học viên">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                             
                                            @endif
                                            
                                            @if($trangThai == 'tu_choi' && $dangKy->ly_do_tu_choi)
                                                @php
                                                    $lyDoTuChoi = addslashes($dangKy->ly_do_tu_choi);
                                                @endphp
                                                <button type="button" onclick="showLyDoModal('{{ $dangKy->id }}', '{{ $lyDoTuChoi }}')" class="bg-gray-100 text-gray-600 p-2 rounded-md hover:bg-gray-200" title="Xem lý do từ chối">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            @endif
                                            
                                            <form action="{{ route('giao-vien.lop-hoc.remove-student', ['id' => $lopHoc->id, 'hocVienId' => $dangKy->hocVien->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học viên này khỏi lớp học?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-md hover:bg-red-200" title="Xóa">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Chưa có học viên nào đăng ký lớp học này.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal từ chối -->
    <div id="tu-choi-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="tu-choi-form" action="" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-times text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="tu-choi-title">
                                    Từ chối học viên
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Vui lòng nhập lý do từ chối học viên này:
                                    </p>
                                    <textarea name="ly_do_tu_choi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Nhập lý do từ chối..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Xác nhận từ chối
                        </button>
                        <button type="button" onclick="hideTuChoiModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal xem lý do từ chối -->
    <div id="ly-do-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-info-circle text-gray-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Lý do từ chối
                            </h3>
                            <div class="mt-2">
                                <div id="ly-do-content" class="p-3 bg-gray-50 rounded-md text-sm text-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="hideLyDoModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function showTuChoiModal(dangKyId, hoTen) {
        document.getElementById('tu-choi-title').innerText = 'Từ chối học viên: ' + hoTen;
        const url = "{{ route('giao-vien.tu-choi-hoc-vien', ['id' => $lopHoc->id, 'dangKyId' => ':dangKyId']) }}";
        document.getElementById('tu-choi-form').action = url.replace(':dangKyId', dangKyId);
        document.getElementById('tu-choi-modal').classList.remove('hidden');
    }
    
    function hideTuChoiModal() {
        document.getElementById('tu-choi-modal').classList.add('hidden');
    }
    
    function showLyDoModal(dangKyId, lyDo) {
        document.getElementById('ly-do-content').innerText = lyDo;
        document.getElementById('ly-do-modal').classList.remove('hidden');
    }
    
    function hideLyDoModal() {
        document.getElementById('ly-do-modal').classList.add('hidden');
    }
    
    // Đóng modal khi click bên ngoài
    window.onclick = function(event) {
        const tuChoiModal = document.getElementById('tu-choi-modal');
        const lyDoModal = document.getElementById('ly-do-modal');
        
        if (event.target == tuChoiModal) {
            hideTuChoiModal();
        }
        
        if (event.target == lyDoModal) {
            hideLyDoModal();
        }
    }
</script>
@endsection 