@extends('layouts.dashboard')

@section('title', 'Danh sách học viên lớp ' . $lopHoc->ten)
@section('page-heading', 'Danh sách học viên lớp ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách học viên lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
              
            </div>
        </div>
    </div>
    
    <!-- Thông tin lớp học -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin lớp học</h3>
                <p class="mt-1 text-sm text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</p>
                <p class="mt-1 text-sm text-gray-900">Hình thức: {{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
                <p class="mt-1 text-sm text-gray-900">Học phí: {{ number_format($lopHoc->khoaHoc->hoc_phi, 0, ',', '.') }} đ</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Giáo viên</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}</p>
                <p class="mt-1 text-sm text-gray-900">Trợ giảng: {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa phân công' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thời gian học</h3>
                <p class="mt-1 text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                <p class="mt-1 text-sm text-gray-900">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
                <p class="mt-1 text-sm text-gray-900">Lịch học: {{ $lopHoc->lich_hoc }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thống kê nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $dangKyHocs->count() }}</h3>
                    <p class="text-sm text-gray-500">Tổng số học viên</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $confirmedStudents->count() }}</h3>
                    <p class="text-sm text-gray-500">Đã xác nhận</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $pendingStudents->count() }}</h3>
                    <p class="text-sm text-gray-500">Chờ xác nhận</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ max(0, $lopHoc->so_luong_toi_da - $confirmedStudents->count()) }}</h3>
                    <p class="text-sm text-gray-500">Còn trống</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Nav -->
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px" id="tabDanhSachHocVien" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-red-600 rounded-t-lg active" id="all-tab" data-tabs-target="#tab-all" type="button" role="tab" aria-controls="tab-all" aria-selected="true">
                    Tất cả ({{ $dangKyHocs->count() }})
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:border-gray-300" id="confirmed-tab" data-tabs-target="#tab-confirmed" type="button" role="tab" aria-controls="tab-confirmed" aria-selected="false">
                    Đã xác nhận ({{ $confirmedStudents->count() }})
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:border-gray-300" id="pending-tab" data-tabs-target="#tab-pending" type="button" role="tab" aria-controls="tab-pending" aria-selected="false">
                    Chờ xác nhận ({{ $pendingStudents->count() }})
                </button>
            </li>
        </ul>
    </div>
    
    <!-- Tab Content -->
    <div id="tabContentDanhSachHocVien">
        <!-- Tất cả học viên -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6" id="tab-all" role="tabpanel" aria-labelledby="all-tab">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
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
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dangKyHocs as $index => $dangKy)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                            {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dangKy->hocVien->nguoiDung->ho_ten ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->email ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->so_dien_thoai ?? 'N/A' }}
                                    </div>
                                </td>
                              
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        
                                        switch($dangKy->trang_thai) {
                                            case 'cho_xac_nhan':
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Chờ xác nhận';
                                                break;
                                            case 'da_thanh_toan':
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'Đã thanh toán';
                                                break;
                                            case 'da_xac_nhan':
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Đã xác nhận';
                                                break;
                                        }
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end">
                                        <a href="{{ route('admin.hoc-vien.show', $dangKy->hocVien->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($dangKy->trang_thai == 'cho_xac_nhan')
                                            <form action="{{ route('admin.dang-ky-hoc.xac-nhan', $dangKy->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Xác nhận">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.lop-hoc.remove-student', ['id' => $lopHoc->id, 'dangKyId' => $dangKy->id]) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học viên này khỏi lớp học?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Chưa có học viên nào đăng ký lớp học này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Đã xác nhận -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6 hidden" id="tab-confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
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
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($confirmedStudents as $index => $dangKy)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                            {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dangKy->hocVien->nguoiDung->ho_ten ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->email ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->so_dien_thoai ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đã xác nhận
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end">
                                        <a href="{{ route('admin.hoc-vien.show', $dangKy->hocVien->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.lop-hoc.remove-student', ['id' => $lopHoc->id, 'dangKyId' => $dangKy->id]) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học viên này khỏi lớp học?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Chưa có học viên nào được xác nhận trong lớp học này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chờ xác nhận -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6 hidden" id="tab-pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
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
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendingStudents as $index => $dangKy)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                            {{ strtoupper(substr($dangKy->hocVien->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dangKy->hocVien->nguoiDung->ho_ten ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->email ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $dangKy->hocVien->nguoiDung->so_dien_thoai ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($dangKy->ngay_dang_ky)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Chờ xác nhận
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end">
                                        <a href="{{ route('admin.hoc-vien.show', $dangKy->hocVien->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.dang-ky-hoc.xac-nhan', $dangKy->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Xác nhận">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.lop-hoc.remove-student', ['id' => $lopHoc->id, 'dangKyId' => $dangKy->id]) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học viên này khỏi lớp học?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Không có học viên nào đang chờ xác nhận.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
<script>
    // Tabs control
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('[data-tabs-target]');
        const tabContents = document.querySelectorAll('[role="tabpanel"]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = document.querySelector(tab.dataset.tabsTarget);
                
                tabContents.forEach(tabContent => {
                    tabContent.classList.add('hidden');
                });
                
                tabs.forEach(t => {
                    t.classList.remove('active', 'border-red-600');
                    t.classList.add('border-transparent');
                    t.setAttribute('aria-selected', false);
                });
                
                target.classList.remove('hidden');
                tab.classList.add('active', 'border-red-600');
                tab.classList.remove('border-transparent');
                tab.setAttribute('aria-selected', true);
            });
        });
    });
</script>
@endpush 