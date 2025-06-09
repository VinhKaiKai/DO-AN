@extends('layouts.dashboard')

@section('title', 'Quản lý bình luận')
@section('page-heading', 'Quản lý bình luận')

@php
$active = 'binh-luan';
$role = 'tro_giang';
@endphp

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý bình luận</h1>
    </div>

    <!-- Filter lớp học và loại bình luận -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('tro-giang.binh-luan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-full md:w-1/3">
                <label for="lop_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Lọc theo lớp học</label>
                <select id="lop_hoc_id" name="lop_hoc_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                    <option value="">Tất cả lớp học</option>
                    @foreach($lopHocs as $lopHoc)
                        <option value="{{ $lopHoc->id }}" {{ $lopHocId == $lopHoc->id ? 'selected' : '' }}>
                            {{ $lopHoc->ten }} ({{ $lopHoc->khoaHoc->ten }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="vai_tro" class="block text-sm font-medium text-gray-700 mb-1">Lọc theo vai trò</label>
                <select id="vai_tro" name="vai_tro" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                    <option value="">Tất cả vai trò</option>
                    <option value="hoc_vien" {{ $vaiTro == 'hoc_vien' ? 'selected' : '' }}>Học viên</option>
                    <option value="giao_vien" {{ $vaiTro == 'giao_vien' ? 'selected' : '' }}>Giáo viên</option>
                    <option value="tro_giang" {{ $vaiTro == 'tro_giang' ? 'selected' : '' }}>Trợ giảng</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Lọc
                </button>
            </div>
            
            <div class="ml-auto">
                <a href="{{ route('tro-giang.binh-luan.index', ['vai_tro' => 'hoc_vien']) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Bình luận cần phản hồi
                </a>
            </div>
        </form>
    </div>
    
    <!-- Thông tin tóm tắt -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Bình luận của học viên</h2>
                        <p class="text-2xl font-bold text-blue-600">{{ $binhLuans->where('nguoiDung.vaiTro.0.ten', 'hoc_vien')->count() }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Bình luận của bạn</h2>
                        <p class="text-2xl font-bold text-green-600">{{ $binhLuans->where('nguoi_dung_id', session('nguoi_dung_id'))->count() }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Tổng số lớp học</h2>
                        <p class="text-2xl font-bold text-yellow-600">{{ $lopHocs->count() }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Tổng bình luận</dt>
                <p class="text-2xl font-bold text-blue-600">{{ $binhLuans->count() }}</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Bình luận từ học viên</dt>
                <p class="text-2xl font-bold text-blue-600">{{ $binhLuans->filter(function($item) {
                    return $item->nguoiDung && $item->nguoiDung->vaiTro && $item->nguoiDung->vaiTro->ten == 'hoc_vien';
                })->count() }}</p>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Bình luận cần phản hồi</dt>
                <p class="text-2xl font-bold text-blue-600">{{ $binhLuans->filter(function($item) {
                    return $item->nguoiDung && $item->nguoiDung->vaiTro && $item->nguoiDung->vaiTro->ten == 'hoc_vien' && !$item->da_phan_hoi;
                })->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Danh sách bình luận -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">
                @if(request('vai_tro') == 'hoc_vien')
                    Danh sách bình luận cần phản hồi
                @else
                    Danh sách bình luận
                @endif
            </h3>
        </div>
        
        @if($binhLuans->isEmpty())
            <div class="px-4 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không có bình luận nào</h3>
                <p class="mt-1 text-sm text-gray-500">Hiện tại chưa có bình luận nào trong các lớp học của bạn.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($binhLuans as $binhLuan)
                    <!-- Khối bình luận -->
                    <div class="p-4 {{ $binhLuan->nguoiDung && $binhLuan->nguoiDung->vaiTro && $binhLuan->nguoiDung->vaiTro->ten == 'hoc_vien' ? 'bg-blue-50' : 'bg-white' }}" id="binh-luan-{{ $binhLuan->id }}">
                        <!-- Thông tin người bình luận và nội dung -->
                        <div class="flex space-x-3">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full 
                                    @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'giao_vien')
                                        bg-blue-100
                                    @elseif(optional($binhLuan->nguoiDung->vaiTro)->ten == 'tro_giang')
                                        bg-green-100
                                    @else
                                        bg-yellow-100
                                    @endif
                                    flex items-center justify-center">
                                    <span class="
                                        @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'giao_vien')
                                            text-blue-600
                                        @elseif(optional($binhLuan->nguoiDung->vaiTro)->ten == 'tro_giang')
                                            text-green-600
                                        @else
                                            text-yellow-600
                                        @endif
                                        font-medium">
                                        {{ strtoupper(substr($binhLuan->nguoiDung->ho, 0, 1)) . strtoupper(substr($binhLuan->nguoiDung->ten, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Nội dung bình luận -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $binhLuan->nguoiDung->ho . ' ' . $binhLuan->nguoiDung->ten }}
                                    </p>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'giao_vien')
                                            bg-blue-100 text-blue-800
                                        @elseif(optional($binhLuan->nguoiDung->vaiTro)->ten == 'tro_giang')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-yellow-100 text-yellow-800
                                        @endif
                                    ">
                                        @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'giao_vien')
                                            Giáo viên
                                        @elseif(optional($binhLuan->nguoiDung->vaiTro)->ten == 'tro_giang')
                                            Trợ giảng
                                        @else
                                            Học viên
                                        @endif
                                    </span>
                                    
                                    @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'hoc_vien' && !$binhLuan->da_phan_hoi)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Cần phản hồi
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Thông tin lớp học và bài học -->
                                <div class="text-xs text-gray-500 mt-0.5">
                                    <span>{{ \Carbon\Carbon::parse($binhLuan->tao_luc)->format('d/m/Y H:i') }}</span> - 
                                    <span>
                                        Lớp: <a href="{{ route('tro-giang.lop-hoc.show', $binhLuan->lopHoc->id) }}" class="text-red-600 hover:text-red-500">
                                            {{ $binhLuan->lopHoc->ten }}
                                        </a>
                                    </span> - 
                                    <span>
                                        Bài học: <a href="{{ route('tro-giang.bai-hoc.show', [$binhLuan->lopHoc->id, $binhLuan->baiHoc->id]) }}" class="text-red-600 hover:text-red-500">
                                            {{ $binhLuan->baiHoc->tieu_de }}
                                        </a>
                                    </span>
                                </div>
                                
                                <!-- Nội dung bình luận -->
                                <div class="mt-2 text-sm text-gray-700 p-3 bg-gray-50 rounded-lg">
                                    <p>{{ $binhLuan->noi_dung }}</p>
                                </div>
                                
                                <!-- Các nút tương tác -->
                                <div class="mt-2 flex justify-between items-center">
                                    <!-- Nút phản hồi -->
                                    @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'hoc_vien')
                                        <button type="button" 
                                                class="text-sm text-blue-600 hover:text-blue-800 flex items-center"
                                                onclick="togglePhanHoiForm('phan-hoi-form-{{ $binhLuan->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            @if($binhLuan->da_phan_hoi)
                                                Phản hồi thêm
                                            @else
                                                Phản hồi
                                            @endif
                                        </button>
                                    @else
                                        <span></span>
                                    @endif
                                    
                                    <!-- Nút xóa -->
                                    @if($binhLuan->nguoi_dung_id == session('nguoi_dung_id'))
                                        <form action="{{ route('tro-giang.binh-luan.destroy', $binhLuan->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Xóa
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <!-- Form phản hồi -->
                                @if(optional($binhLuan->nguoiDung->vaiTro)->ten == 'hoc_vien')
                                    <div id="phan-hoi-form-{{ $binhLuan->id }}" class="mt-3 hidden">
                                        <div class="flex items-start space-x-3">
                                            <!-- Đường kẻ nối từ avatar tới phản hồi -->
                                            <div class="flex flex-col items-center">
                                                <div class="w-0.5 h-6 bg-gray-300"></div>
                                            </div>
                                            
                                            <!-- Form phản hồi -->
                                            <div class="flex-1 bg-gray-100 p-3 rounded-lg border border-gray-200">
                                                <form action="{{ route('tro-giang.binh-luan.phan-hoi') }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <input type="hidden" name="bai_hoc_id" value="{{ $binhLuan->bai_hoc_id }}">
                                                    <input type="hidden" name="lop_hoc_id" value="{{ $binhLuan->lop_hoc_id }}">
                                                    <input type="hidden" name="binh_luan_goc_id" value="{{ $binhLuan->id }}">
                                                    
                                                    <div class="flex items-center">
                                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                                            <span class="text-green-600 font-medium text-xs">
                                                                TG
                                                            </span>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            Phản hồi cho <span class="font-medium">{{ $binhLuan->nguoiDung->ho . ' ' . $binhLuan->nguoiDung->ten }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <textarea name="noi_dung" rows="2" required 
                                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="Viết phản hồi của bạn..."></textarea>
                                                    
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button" 
                                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                                onclick="togglePhanHoiForm('phan-hoi-form-{{ $binhLuan->id }}')">
                                                            Hủy
                                                        </button>
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Gửi phản hồi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Hiển thị phản hồi -->
                                @if($binhLuan->phanHois->count() > 0)
                                    @foreach($binhLuan->phanHois as $phanHoi)
                                    <div class="mt-3 pl-4 border-l-2 border-gray-200">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-green-600">
                                                        {{ strtoupper(substr($phanHoi->nguoiDung->ho, 0, 1)) . strtoupper(substr($phanHoi->nguoiDung->ten, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $phanHoi->nguoiDung->ho . ' ' . $phanHoi->nguoiDung->ten }}</p>
                                                <div class="mt-1 text-sm text-gray-700 bg-gray-50 p-2 rounded-lg">
                                                    <p>{{ $phanHoi->noi_dung }}</p>
                                                </div>
                                                <div class="mt-1 flex justify-between items-center">
                                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($phanHoi->tao_luc)->format('d/m/Y H:i') }}</p>
                                                    
                                                    @if($phanHoi->nguoi_dung_id == session('nguoi_dung_id'))
                                                        <form action="{{ route('tro-giang.binh-luan.destroy', $phanHoi->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phản hồi này?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900 flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $binhLuans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Script để hiển thị/ẩn form phản hồi -->
<script>
function togglePhanHoiForm(formId) {
    const form = document.getElementById(formId);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }
}
</script>
@endsection 