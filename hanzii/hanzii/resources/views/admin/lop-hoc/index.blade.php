@extends('layouts.dashboard')

@section('title', 'Quản lý lớp học')
@section('page-heading', 'Quản lý lớp học')

@php
    $active = 'lop-hoc';
    $role = 'admin';
@endphp

@section('styles')
<style>
    @media (max-width: 640px) {
        .search-form-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .search-form-buttons > * {
            margin-top: 0.5rem;
            margin-left: 0 !important;
            width: 100%;
        }
        
        .search-form-buttons > *:first-child {
            margin-top: 0;
        }
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Cố định kích thước cột để tránh bảng quá rộng */
    .col-fixed-sm {
        width: 120px;
        min-width: 120px;
    }
    .col-fixed-md {
        width: 160px;
        min-width: 160px;
    }
    .col-fixed-lg {
        width: 200px;
        min-width: 200px;
    }
    .col-actions {
        width: 100px;
        min-width: 100px;
    }

    /* Xử lý văn bản dài */
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    /* Card view cho màn hình nhỏ */
    @media (max-width: 768px) {
        .mobile-card {
            display: block;
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .mobile-card-header {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .mobile-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .mobile-card-label {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .mobile-card-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.75rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md border border-blue-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 text-white rounded-lg">
                    <i class="fas fa-school text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ number_format($tong_lop) }}</h2>
                    <p class="text-sm text-gray-600">Tổng số lớp học</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-md border border-green-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 text-white rounded-lg">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ number_format($dang_dien_ra) }}</h2>
                    <p class="text-sm text-gray-600">Lớp đang diễn ra</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg shadow-md border border-amber-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-amber-500 text-white rounded-lg">
                    <i class="fas fa-hourglass-start text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ number_format($sap_khai_giang) }}</h2>
                    <p class="text-sm text-gray-600">Lớp sắp khai giảng</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-md border border-purple-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 text-white rounded-lg">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ number_format($da_ket_thuc) }}</h2>
                    <p class="text-sm text-gray-600">Lớp đã kết thúc</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê bổ sung và tác vụ nhanh -->
    <div class="flex flex-col sm:flex-row sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-users text-indigo-500 mr-2"></i>
                    <span>Tổng học viên: <strong>{{ number_format($tong_hoc_vien) }}</strong></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                    <span>Tổng giáo viên: <strong>{{ number_format($tong_giao_vien) }}</strong></span>
                </div>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.lop-hoc.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                <i class="fas fa-plus mr-2"></i> Thêm lớp học
            </a>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <form action="{{ route('admin.lop-hoc.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="min-w-max col-span-1 md:col-span-2 lg:col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-red-500 focus:border-red-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Tên lớp, mã lớp">
                </div>
            </div>
            <div class="w-full">
                <label for="khoa_hoc_id" class="block text-sm font-medium text-gray-700">Khóa học</label>
                <select id="khoa_hoc_id" name="khoa_hoc_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                    <option value="">Tất cả khóa học</option>
                    @foreach($khoaHocs as $khoaHoc)
                        <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>
                            {{ $khoaHoc->ten }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label for="trang_thai" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                    <option value="tat_ca">Tất cả trạng thái</option>
                    <option value="sap_khai_giang" {{ request('trang_thai') == 'sap_khai_giang' ? 'selected' : '' }}>Sắp khai giảng</option>
                    <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="da_ket_thuc" {{ request('trang_thai') == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
            <div class="flex space-x-2 col-span-1 lg:justify-end search-form-buttons">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full md:w-auto">
                    <i class="fas fa-filter mr-2"></i> Lọc
                </button>
                <a href="{{ route('admin.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                    <i class="fas fa-redo mr-2"></i> Đặt lại
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách lớp học - Desktop view -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden hidden md:block">
        <div class="table-responsive">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fixed-lg">Lớp học</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fixed-md">Khóa học</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fixed-lg">Giáo viên/Trợ giảng</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fixed-sm">Ngày bắt đầu</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider col-fixed-sm">Trạng thái</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider col-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lopHocs as $lopHoc)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 text-truncate" title="{{ $lopHoc->ten }}">
                                        {{ $lopHoc->ten }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Mã: {{ $lopHoc->ma_lop }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 text-truncate" title="{{ $lopHoc->khoaHoc->ten }}">
                                {{ $lopHoc->khoaHoc->ten }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 text-truncate" title="{{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}">
                                {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}
                            </div>
                            <div class="text-xs text-gray-500 text-truncate" title="TG: {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa phân công' }}">
                                TG: {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa phân công' }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại TT' }}</div>
                        </td>
                       
                        <td class="px-4 py-3">
                            @if($lopHoc->trang_thai == 'sap_khai_giang')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Sắp khai giảng
                                </span>
                            @elseif($lopHoc->trang_thai == 'dang_dien_ra' )
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Đang diễn ra
                                </span>
                            @elseif($lopHoc->trang_thai == 'da_ket_thuc' || $lopHoc->trang_thai == 'da_hoan_thanh')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Đã kết thúc
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $lopHoc->trang_thai }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.lop-hoc.edit', $lopHoc->id) }}" class="text-amber-600 hover:text-amber-900" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="text-blue-600 hover:text-blue-900" title="Danh sách học viên">
                                    <i class="fas fa-users"></i>
                                </a>
                                <form action="{{ route('admin.lop-hoc.destroy', $lopHoc->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-sm text-gray-500 text-center">
                            <div class="my-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                </svg>
                                <p class="mt-2 text-base text-gray-900">Không tìm thấy lớp học nào</p>
                                <p class="mt-1 text-sm text-gray-500">Hãy thử thay đổi bộ lọc hoặc thêm lớp học mới.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Danh sách lớp học - Mobile view -->
    <div class="md:hidden space-y-4">
        @forelse($lopHocs as $lopHoc)
        <div class="bg-white shadow-sm rounded-lg p-4 mobile-card">
            <div class="mobile-card-header text-gray-900">{{ $lopHoc->ten }}</div>
            
            <div class="mobile-card-grid">
                <div>
                    <div class="mobile-card-label">Mã lớp</div>
                    <div>{{ $lopHoc->ma_lop }}</div>
                </div>
                <div>
                    <div class="mobile-card-label">Trạng thái</div>
                    <div>
                        @if($lopHoc->trang_thai == 'sap_khai_giang')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Sắp khai giảng
                            </span>
                        @elseif($lopHoc->trang_thai == 'dang_dien_ra' )
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đang diễn ra
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Đã kết thúc
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mobile-card-grid">
                <div>
                    <div class="mobile-card-label">Khóa học</div>
                    <div>{{ $lopHoc->khoaHoc->ten }}</div>
                </div>
                <div>
                    <div class="mobile-card-label">Ngày bắt đầu</div>
                    <div>{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="mobile-card-grid">
                <div>
                    <div class="mobile-card-label">Giáo viên</div>
                    <div>{{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? 'Chưa phân công' }}</div>
                </div>
                <div>
                    <div class="mobile-card-label">Trợ giảng</div>
                    <div>{{ $lopHoc->troGiang->nguoiDung->ho_ten ?? 'Chưa phân công' }}</div>
                </div>
            </div>

            <div class="mobile-card-actions space-x-3">
                <a href="{{ route('admin.lop-hoc.show', $lopHoc->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.lop-hoc.edit', $lopHoc->id) }}" class="text-amber-600 hover:text-amber-900" title="Sửa">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('admin.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="text-blue-600 hover:text-blue-900" title="Danh sách học viên">
                    <i class="fas fa-users"></i>
                </a>
                <form action="{{ route('admin.lop-hoc.destroy', $lopHoc->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
            </svg>
            <p class="mt-2 text-base text-gray-900">Không tìm thấy lớp học nào</p>
            <p class="mt-1 text-sm text-gray-500">Hãy thử thay đổi bộ lọc hoặc thêm lớp học mới.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Phân trang -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $lopHocs->withQueryString()->links() }}
    </div>
</div>
@endsection
