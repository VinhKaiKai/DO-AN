@extends('layouts.dashboard')

@section('title', 'Chi tiết lớp học')
@section('page-heading', 'Chi tiết lớp học')

@php
    $active = 'lop-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Chi tiết lớp học: {{ $lopHoc->ma_lop }}</h2>
            <div class="mt-4 md:mt-0 flex space-x-2">
              
                <a href="{{ route('admin.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Thông tin lớp học -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Thông tin lớp học</h3>
                    <div>
                        @php
                            $statusClass = '';
                            $statusText = '';
                            
                            if ($lopHoc->trang_thai == 'sap_khai_giang') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'Sắp khai giảng';
                            } elseif ($lopHoc->trang_thai == 'dang_dien_ra') {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Đang diễn ra';
                            } else {
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Đã kết thúc';
                            }
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-medium rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-6">
                                <h4 class="text-base font-medium text-gray-900 mb-2">Thông tin cơ bản</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Tên lớp:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Mã lớp:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->ma_lop }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Khóa học:</span>
                                        <a href="{{ route('admin.khoa-hoc.show', $lopHoc->khoa_hoc_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            {{ $lopHoc->khoaHoc->ten }}
                                        </a>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Hình thức học:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Sĩ số tối đa:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->so_luong_toi_da }} học viên</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-2">Về khóa học</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Học phí:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($lopHoc->khoaHoc->hoc_phi, 0, ',', '.') }} đ</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Thời lượng:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->khoaHoc->thoi_gian_hoan_thanh }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <h4 class="text-base font-medium text-gray-900 mb-2">Thời gian</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Lịch học:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $lopHoc->lich_hoc }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Ngày bắt đầu:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Ngày kết thúc:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Thời gian còn lại:</span>
                                        @php
                                            $remaining = '';
                                            if ($lopHoc->trang_thai == 'sap_khai_giang') {
                                                $days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($lopHoc->ngay_bat_dau), false);
                                                $remaining = $days . ' ngày nữa sẽ khai giảng';
                                            } elseif ($lopHoc->trang_thai == 'dang_dien_ra') {
                                                $days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($lopHoc->ngay_ket_thuc), false);
                                                $remaining = $days . ' ngày nữa kết thúc';
                                            } else {
                                                $remaining = 'Đã kết thúc';
                                            }
                                        @endphp
                                        <span class="text-sm font-medium text-gray-900">{{ $remaining }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-2">Giảng viên</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Giáo viên:</span>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                                {{ $lopHoc->giaoVien && $lopHoc->giaoVien->nguoiDung ? strtoupper(substr($lopHoc->giaoVien->nguoiDung->ho_ten, 0, 1)) : 'N/A' }}
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $lopHoc->giaoVien && $lopHoc->giaoVien->nguoiDung ? $lopHoc->giaoVien->nguoiDung->ho_ten : 'Chưa phân công' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Trợ giảng:</span>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                                                {{ $lopHoc->troGiang && $lopHoc->troGiang->nguoiDung ? strtoupper(substr($lopHoc->troGiang->nguoiDung->ho_ten, 0, 1)) : 'N/A' }}
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $lopHoc->troGiang && $lopHoc->troGiang->nguoiDung ? $lopHoc->troGiang->nguoiDung->ho_ten : 'Chưa phân công' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($lopHoc->ghi_chu)
                        <div class="mt-6 bg-gray-50 rounded-md p-4">
                            <h4 class="text-base font-medium text-gray-900 mb-2">Ghi chú</h4>
                            <p class="text-sm text-gray-600">{{ $lopHoc->ghi_chu }}</p>
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200 flex flex-wrap gap-2">
                        <a href="{{ route('admin.lop-hoc.edit', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                        </a>
                        
                        <a href="{{ route('admin.lop-hoc.yeu-cau-tham-gia', $lopHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-user-plus mr-2"></i>
                            Yêu cầu tham gia 
                            @php
                                $countPendingRequests = \App\Models\YeuCauThamGia::where('lop_hoc_id', $lopHoc->id)
                                    ->where('trang_thai', 'cho_xac_nhan')
                                    ->count();
                            @endphp
                            @if($countPendingRequests > 0)
                                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800">{{ $countPendingRequests }}</span>
                            @endif
                        </a>

                        <form action="{{ route('admin.lop-hoc.destroy', $lopHoc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lớp học này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash mr-2"></i> Xóa lớp học
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <!-- Thống kê lớp học -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thống kê lớp học</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-600">Số học viên:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $lopHoc->dangKyHocs->where('trang_thai', 'da_xac_nhan')->count() }}/{{ $lopHoc->so_luong_toi_da }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                            $percent = ($lopHoc->dangKyHocs->where('trang_thai', 'da_xac_nhan')->count() / max(1, $lopHoc->so_luong_toi_da)) * 100;
                            $percentStyle = 'width: ' . $percent . '%;';
                            @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full" {!! 'style="' . $percentStyle . '"' !!}></div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Đã xác nhận:</span>
                            <span class="text-xs font-medium text-gray-900">{{ $lopHoc->dangKyHocs->where('trang_thai', 'da_xac_nhan')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Chờ xác nhận:</span>
                            <span class="text-xs font-medium text-gray-900">{{ $lopHoc->dangKyHocs->where('trang_thai', 'cho_xac_nhan')->count() }}</span>
                        </div>
                      
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Còn trống:</span>
                            <span class="text-xs font-medium text-gray-900">{{ max(0, $lopHoc->so_luong_toi_da - $lopHoc->dangKyHocs->where('trang_thai', 'da_xac_nhan')->count()) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('admin.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="inline-flex justify-center items-center text-xs font-medium text-blue-600 hover:text-blue-800">
                                <i class="fas fa-users mr-1"></i> Danh sách học viên
                            </a>
                            <a href="{{ route('admin.lop-hoc.yeu-cau-tham-gia', $lopHoc->id) }}" class="inline-flex justify-center items-center text-xs font-medium text-blue-600 hover:text-blue-800">
                                <i class="fas fa-user-plus mr-1"></i> Yêu cầu tham gia
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thời gian học -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tiến độ học tập</h3>
                </div>
                <div class="p-6">
                    @php
                        $totalDays = max(1, \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->diffInDays(\Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)));
                        $passedDays = min($totalDays, max(0, \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->diffInDays(\Carbon\Carbon::now())));
                        $progress = min(100, ($passedDays / $totalDays) * 100);
                    @endphp

                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-600">Tiến độ:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                            $progressStyle = 'width: ' . number_format($progress, 0) . '%;';
                            @endphp
                            <div class="bg-green-600 h-2.5 rounded-full" {!! 'style="' . $progressStyle . '"' !!}></div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày bắt đầu:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày kết thúc:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Thời gian học:</span>
                            <span class="font-medium text-gray-900">{{ $totalDays }} ngày</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Đã học:</span>
                            <span class="font-medium text-gray-900">{{ $passedDays }} ngày</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Còn lại:</span>
                            <span class="font-medium text-gray-900">{{ max(0, $totalDays - $passedDays) }} ngày</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
@endsection 