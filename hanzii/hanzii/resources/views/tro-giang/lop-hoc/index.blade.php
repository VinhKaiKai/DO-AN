@extends('layouts.dashboard')

@section('title', 'Danh sách lớp học')
@section('page-heading', 'Danh sách lớp học')

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('tro-giang.lop-hoc.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="sap_khai_giang" {{ request('trang_thai') == 'sap_khai_giang' ? 'selected' : '' }}>Sắp khai giảng</option>
                    <option value="da_ket_thuc" {{ request('trang_thai') == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full md:w-auto inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Lọc
                </button>
            </div>
        </form>
    </div>
    
    <!-- Danh sách lớp học -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-800">Danh sách lớp học đang phụ trách</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên lớp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thuộc khóa học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lopHocs as $lopHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</div>
                                    @if(in_array($lopHoc->id, $lopHocIdsCanPhanHoi ?? []))
                                        <span class="ml-2 flex-shrink-0 h-2 w-2 rounded-full bg-red-600" title="Có bình luận cần phản hồi"></span>
                                    @endif
                                </div>
                                @if($lopHoc->ma_lop)
                                    <div class="text-xs text-gray-500">Mã lớp: {{ $lopHoc->ma_lop }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lopHoc->khoaHoc->ten }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</div>
                                @if($lopHoc->ngay_ket_thuc)
                                    <div class="text-sm text-gray-500">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</div>
                                @endif
                            </td>
                         
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($lopHoc->trang_thai == 'dang_dien_ra') bg-green-100 text-green-800
                                    @elseif($lopHoc->trang_thai == 'sap_khai_giang') bg-yellow-100 text-yellow-800
                                    @elseif($lopHoc->trang_thai == 'da_ket_thuc') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    @if($lopHoc->trang_thai == 'dang_dien_ra')
                                        Đang diễn ra
                                    @elseif($lopHoc->trang_thai == 'sap_khai_giang')
                                        Sắp khai giảng
                                    @elseif($lopHoc->trang_thai == 'da_ket_thuc')
                                        Đã kết thúc
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $lopHoc->trang_thai)) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('tro-giang.lop-hoc.show', $lopHoc->id) }}" class="text-red-600 hover:text-red-900">Chi tiết</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Không tìm thấy lớp học nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Phân trang -->
        @if($lopHocs->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $lopHocs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 