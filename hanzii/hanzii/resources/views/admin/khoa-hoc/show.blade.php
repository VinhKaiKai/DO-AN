@extends('layouts.dashboard')

@section('title', 'Chi tiết khóa học')
@section('page-heading', 'Chi tiết khóa học')

@php
    $active = 'khoa-hoc';
    $role = 'admin';
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Chi tiết khóa học</h2>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('admin.khoa-hoc.edit', $khoaHoc->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-edit mr-2"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.khoa-hoc.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Thông tin khóa học và danh sách bài học -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin khóa học -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Thông tin khóa học</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $khoaHoc->trang_thai == 'hoat_dong' ? 'bg-green-100 text-green-800' : 
                        ($khoaHoc->trang_thai == 'tam_ngung' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $khoaHoc->trang_thai == 'hoat_dong' ? 'Đang hoạt động' : 
                        ($khoaHoc->trang_thai == 'tam_ngung' ? 'Tạm ngưng' : 'Đã kết thúc') }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $khoaHoc->ten }}</h3>
                        <div class="mb-4">
                            @if($khoaHoc->hinh_anh)
                                <img src="{{ Storage::url($khoaHoc->hinh_anh) }}" alt="{{ $khoaHoc->ten }}" class="w-full h-56 object-cover rounded-md">
                            @else
                                <div class="bg-gray-100 rounded-md text-center py-8">
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                    <p class="mt-2 text-sm text-gray-500">Chưa có hình ảnh</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="font-medium mr-2">Học phí:</span> 
                                <span class="text-gray-900">{{ number_format($khoaHoc->hoc_phi, 0, ',', '.') }} đ</span>
                            </p>
                            <p class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="font-medium mr-2">Tổng số bài học:</span> 
                                <span class="text-gray-900">{{ $khoaHoc->tong_so_bai ?? 'Chưa cập nhật' }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="font-medium mr-2">Ngày tạo:</span> 
                                <span class="text-gray-900">{{ $khoaHoc->tao_luc ? \Carbon\Carbon::parse($khoaHoc->tao_luc)->format('d/m/Y H:i') : 'N/A' }}</span>
                            </p>
                            <p class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="font-medium mr-2">Cập nhật lần cuối:</span> 
                                <span class="text-gray-900">{{ $khoaHoc->cap_nhat_luc ? \Carbon\Carbon::parse($khoaHoc->cap_nhat_luc)->format('d/m/Y H:i') : 'N/A' }}</span>
                            </p>
                            <p class="flex items-center text-sm text-gray-600 mb-2">
                                <span class="font-medium mr-2">Thời gian hoàn thành:</span> 
                                <span class="text-gray-900">{{ $khoaHoc->thoi_gian_hoan_thanh ?? 'Chưa cập nhật' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-base font-semibold text-gray-900 mb-2">Mô tả khóa học</h4>
                        <div class="p-4 bg-gray-50 rounded-md text-sm text-gray-800">
                            {!! $khoaHoc->mo_ta !!}
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 flex flex-wrap gap-2">
                        <a href="{{ route('admin.khoa-hoc.edit', $khoaHoc->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-200 active:bg-blue-700 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.lop-hoc.create') }}" method="GET">
                            <input type="hidden" name="khoa_hoc_id" value="{{ $khoaHoc->id }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-200 active:bg-green-700 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i> Mở lớp mới
                            </button>
                        </form>
                        <form action="{{ route('admin.khoa-hoc.destroy', $khoaHoc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-200 active:bg-red-700 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-trash mr-2"></i> Xóa khóa học
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Danh sách bài học -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Danh sách bài học</h3>
                    <a href="{{ route('admin.bai-hoc.create', ['khoa_hoc_id' => $khoaHoc->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-200 active:bg-blue-700 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-1"></i> Thêm bài học
                    </a>
                </div>
                <div class="p-6">
                    @if($khoaHoc->baiHocs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên bài học</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại bài</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời lượng</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($khoaHoc->baiHocs->sortBy('thu_tu') as $key => $baiHoc)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $key + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $baiHoc->tieu_de }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($baiHoc->loai == 'video')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Video</span>
                                                @elseif($baiHoc->loai == 'bai_tap')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Bài tập</span>
                                                @elseif($baiHoc->loai == 'tai_lieu')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tài liệu</span>
                                                @elseif($baiHoc->loai == 'van_ban')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Văn bản</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Khác</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $baiHoc->thoi_luong }} phút</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($baiHoc->trang_thai == 'cong_khai' || $baiHoc->trang_thai == 'da_xuat_ban')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Công khai</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Ẩn</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('admin.bai-hoc.show', $baiHoc->id) }}" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.bai-hoc.edit', $baiHoc->id) }}" class="text-green-600 hover:text-green-900" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form class="inline-block" action="{{ route('admin.bai-hoc.destroy', $baiHoc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài học nào</h3>
                            <p class="mt-1 text-sm text-gray-500">Bắt đầu tạo bài học mới cho khóa học này</p>
                           
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar - Thống kê và lớp học đang diễn ra -->
        <div class="space-y-6">
            <!-- Thống kê -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thống kê khóa học</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Tổng số lớp:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $khoaHoc->lopHocs->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Lớp đang diễn ra:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $khoaHoc->lopHocs->where('trang_thai', 'dang_dien_ra')->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php 
                                    $dangDienRaPercent = $khoaHoc->lopHocs->count() > 0 ? ($khoaHoc->lopHocs->where('trang_thai', 'dang_dien_ra')->count() / $khoaHoc->lopHocs->count() * 100) : 0;
                                @endphp
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $dangDienRaPercent }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Lớp sắp khai giảng:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $khoaHoc->lopHocs->where('trang_thai', 'sap_khai_giang')->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php 
                                    $sapDienRaPercent = $khoaHoc->lopHocs->count() > 0 ? ($khoaHoc->lopHocs->where('trang_thai', 'sap_khai_giang')->count() / $khoaHoc->lopHocs->count() * 100) : 0;
                                @endphp
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $sapDienRaPercent }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Lớp đã kết thúc:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $khoaHoc->lopHocs->where('trang_thai', 'da_ket_thuc')->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php 
                                    $daKetThucPercent = $khoaHoc->lopHocs->count() > 0 ? ($khoaHoc->lopHocs->where('trang_thai', 'da_ket_thuc')->count() / $khoaHoc->lopHocs->count() * 100) : 0;
                                @endphp
                                <div class="bg-gray-600 h-2.5 rounded-full" style="width: {{ $daKetThucPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="text-sm font-medium text-gray-700 mb-2">Tổng số học viên đăng ký:</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $tongSoHocVien }}</div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
@endsection 