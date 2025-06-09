@extends('layouts.dashboard')

@section('title', 'Thông báo')
@section('page-heading', 'Quản lý thông báo')

@php
    $active = isset($active) ? $active : 'thong_bao';
    $role = isset($role) ? $role : '';
@endphp

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Phần lọc và tìm kiếm -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('notifications.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="da_doc" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                            <select id="da_doc" name="da_doc" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tất cả</option>
                                <option value="0" {{ request('da_doc') === '0' ? 'selected' : '' }}>Chưa đọc</option>
                                <option value="1" {{ request('da_doc') === '1' ? 'selected' : '' }}>Đã đọc</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="loai" class="block text-sm font-medium text-gray-700 mb-1">Loại thông báo</label>
                            <select id="loai" name="loai" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tất cả</option>
                                <option value="he_thong" {{ request('loai') === 'he_thong' ? 'selected' : '' }}>Hệ thống</option>
                                <option value="thanh_toan" {{ request('loai') === 'thanh_toan' ? 'selected' : '' }}>Thanh toán</option>
                                <option value="lop_hoc" {{ request('loai') === 'lop_hoc' ? 'selected' : '' }}>Lớp học</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-filter mr-2"></i> Lọc
                            </button>
                            <a href="{{ route('notifications.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-sync-alt mr-2"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thông báo -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-5">Danh sách thông báo</h2>
                
                @if ($thongBaos->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Không có thông báo nào</h3>
                        <p class="mt-1 text-sm text-gray-500">Thông báo mới sẽ xuất hiện tại đây.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($thongBaos as $thongBao)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('notifications.show', $thongBao->id) }}" class="block flex-1">
                                        <div class="flex items-center">
                                            <h3 class="text-base font-medium text-gray-900">
                                                {{ $thongBao->tieu_de }}
                                                @if (!$thongBao->da_doc)
                                                    <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Mới
                                                    </span>
                                                @endif
                                            </h3>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $thongBao->noi_dung }}</p>
                                        <div class="mt-1 flex items-center text-xs text-gray-500">
                                            <span>
                                                {{ $thongBao->created_at->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span>
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
                                            </span>
                                        </div>
                                    </a>
                                    
                                    <form action="{{ route('notifications.destroy', $thongBao->id) }}" method="POST" class="ml-2" id="delete-form-{{ $thongBao->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $thongBao->id }})" class="text-red-600 hover:text-red-900" title="Xóa thông báo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-5">
                        {{ $thongBaos->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush 