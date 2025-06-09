@extends('layouts.dashboard')

@section('title', 'Quản lý bài học')
@section('page-heading', 'Chi tiết bài học')

@php
    $active = 'bai-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Thông tin bài học</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.bai-hoc.edit', $baiHoc->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i>Chỉnh sửa
                </a>
                <form action="{{ route('admin.bai-hoc.destroy', $baiHoc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?');" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                        <i class="fas fa-trash-alt mr-1"></i>Xóa
                    </button>
                </form>
                <a href="{{ route('admin.bai-hoc.index') }}" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Quay lại
                </a>
            </div>
        </div>
        
        <div class="p-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Tên bài học</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ $baiHoc->tieu_de }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Khóa học</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $baiHoc->khoaHoc->ten }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Thứ tự</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $baiHoc->so_thu_tu }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Thời lượng (phút)</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $baiHoc->thoi_luong }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Loại bài học</dt>
                    <dd class="mt-1 text-base text-gray-900">
                        @switch($baiHoc->loai)
                            @case('video')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Video</span>
                                @break
                            @case('van_ban')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Văn bản</span>
                                @break
                            @default
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Khác</span>
                        @endswitch
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                    <dd class="mt-1 text-base text-gray-900">
                        @if($baiHoc->trang_thai == 'da_xuat_ban')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đã xuất bản</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Chưa xuất bản</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $baiHoc->tao_luc ? $baiHoc->tao_luc->format('d/m/Y H:i') : 'N/A' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Cập nhật lần cuối</dt>
                    <dd class="mt-1 text-base text-gray-900">{{ $baiHoc->cap_nhat_luc ? $baiHoc->cap_nhat_luc->format('d/m/Y H:i') : 'N/A' }}</dd>
                </div>

                @if($baiHoc->url_video)
                <div class="col-span-2">
                    <dt class="text-sm font-medium text-gray-500">URL Video</dt>
                    <dd class="mt-1 text-base text-blue-600">
                        <a href="{{ $baiHoc->url_video }}" target="_blank" class="hover:underline">
                            {{ $baiHoc->url_video }}
                            <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    </dd>
                </div>
                @endif

                <div class="col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Nội dung</dt>
                    <dd class="mt-2 text-base text-gray-900 bg-gray-50 p-4 rounded border">
                        <div class="prose max-w-none">
                            {!! $baiHoc->noi_dung !!}
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @if($baiHoc->taiLieuBoTros->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Tài liệu bổ trợ</h3>
        </div>
        
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên tài liệu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($baiHoc->taiLieuBoTros as $index => $taiLieu)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $taiLieu->tieu_de }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($taiLieu->mo_ta, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $taiLieu->tao_luc ? $taiLieu->tao_luc->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.tai-lieu.download', $taiLieu->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-download mr-1"></i>Tải xuống
                                    </a>
                                    <form action="{{ route('admin.bai-hoc.xoa-tai-lieu', $taiLieu->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash-alt mr-1"></i>Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($baiHoc->baiTaps->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Bài tập</h3>
        </div>
        
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên bài tập</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thang điểm</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn nộp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($baiHoc->baiTaps as $index => $baiTap)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $baiTap->tieu_de }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @switch($baiTap->loai)
                                  
                                    @case('tu_luan')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Tự luận</span>
                                        @break
                                    @case('file')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Nộp file</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Khác</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $baiTap->diem_toi_da }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $baiTap->han_nop ? $baiTap->han_nop->format('d/m/Y H:i') : 'Không giới hạn' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Xem chi tiết</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endsection 