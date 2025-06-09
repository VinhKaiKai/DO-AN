@extends('layouts.dashboard')

@section('title', 'Chi tiết thông báo')
@section('page-heading', 'Chi tiết thông báo')

@php
    $active = isset($active) ? $active : 'thong_bao';
    $role = isset($role) ? $role : '';
@endphp

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách thông báo
            </a>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-medium text-gray-900">{{ $thongBao->tieu_de }}</h2>
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $thongBao->created_at->format('d/m/Y H:i') }}</span>
                            
                            @switch($thongBao->loai)
                                @case('he_thong')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-cog mr-1"></i> Hệ thống
                                    </span>
                                    @break
                                @case('thanh_toan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Thanh toán
                                    </span>
                                    @break
                                @case('lop_hoc')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> Lớp học
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-bell mr-1"></i> Khác
                                    </span>
                            @endswitch
                        </div>
                    </div>
                </div>
                
                <div class="prose max-w-none">
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        {{ $thongBao->noi_dung }}
                    </div>
                    
                    @if($thongBao->da_doc)
                        <div class="text-sm text-gray-500 mt-4">
                            <i class="fas fa-check-circle mr-1 text-green-500"></i> Đã đọc {{ $thongBao->ngay_doc ? $thongBao->ngay_doc->format('d/m/Y H:i') : '' }}
                        </div>
                    @endif
                    
                    @if($thongBao->url)
                        <div class="mt-6">
                            <a href="{{ $thongBao->url }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-external-link-alt mr-2"></i> Xem liên kết
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6 flex justify-between border-t border-gray-200 pt-6">
                    <form action="{{ route('notifications.destroy', $thongBao->id) }}" method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i> Xóa thông báo
                        </button>
                    </form>
                    
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush 