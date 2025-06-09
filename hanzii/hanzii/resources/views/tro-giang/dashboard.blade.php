@extends('layouts.dashboard')

@section('title', 'Dashboard')

@php
$active = 'dashboard';
$role = 'tro_giang';
@endphp

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard Trợ Giảng</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Danh sách lớp hỗ trợ</h2>
            <a href="{{ route('tro-giang.lop-hoc.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Xem tất cả</a>
        </div>
        
        @if($lopHocs->isEmpty())
            <div class="py-4 text-center text-gray-500">
                Bạn chưa được phân công hỗ trợ lớp học nào.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên lớp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thuộc khóa học</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học viên</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lopHocs as $lopHoc)
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lopHoc->dang_ky_hocs_count ?? '0' }} học viên
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lopHoc->trang_thai == 'dang_dien_ra')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đang diễn ra
                                    </span>
                                @elseif($lopHoc->trang_thai == 'da_hoan_thanh')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Đã hoàn thành
                                    </span>
                                @elseif($lopHoc->trang_thai == 'sap_khai_giang')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Sắp khai giảng
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('tro-giang.lop-hoc.show', $lopHoc->id) }}" class="text-red-600 hover:text-red-900">Chi tiết</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center py-4">
            <p class="text-gray-500">Trợ giảng chỉ có thể bình luận vào bài học của các lớp được phân công.</p>
            <p class="text-gray-700 mt-2">Hãy vào chi tiết lớp học và chọn bài học cụ thể để bình luận.</p>
        </div>
    </div>
</div>
@endsection 