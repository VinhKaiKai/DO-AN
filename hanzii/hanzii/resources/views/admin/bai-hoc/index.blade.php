@extends('layouts.dashboard')

@section('title', 'Quản lý bài học')
@section('page-heading', 'Danh sách bài học')

@php
    $active = 'bai-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Quản lý bài học</h2>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.bai-hoc.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm bài học mới
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('admin.bai-hoc.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Tiêu đề bài học..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            
            <div>
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="chua_xuat_ban" {{ request('trang_thai') == 'chua_xuat_ban' ? 'selected' : '' }}>Chưa xuất bản</option>
                    <option value="da_xuat_ban" {{ request('trang_thai') == 'da_xuat_ban' ? 'selected' : '' }}>Đã xuất bản</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.bai-hoc.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách bài học -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tiêu đề
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khóa học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loại bài học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời lượng
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($baiHocs as $index => $baiHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $baiHoc->so_thu_tu }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $baiHoc->tieu_de }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $baiHoc->khoaHoc->ten ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @switch($baiHoc->loai)
                                        @case('van_ban')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Văn bản
                                            </span>
                                            @break
                                        @case('video')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Video
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Khác
                                            </span>
                                    @endswitch
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $baiHoc->thoi_luong }} phút
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($baiHoc->trang_thai == 'da_xuat_ban')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đã xuất bản
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Chưa xuất bản
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.bai-hoc.show', $baiHoc->id) }}" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bai-hoc.edit', $baiHoc->id) }}" class="text-green-600 hover:text-green-900 ml-3" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="inline-block ml-3" action="{{ route('admin.bai-hoc.destroy', $baiHoc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có bài học nào. <a href="{{ route('admin.bai-hoc.create') }}" class="text-blue-600 hover:text-blue-900">Thêm bài học mới</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $baiHocs->links() }}
        </div>
    </div>
@endsection 