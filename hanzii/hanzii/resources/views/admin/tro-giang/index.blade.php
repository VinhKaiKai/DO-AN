@extends('layouts.dashboard')

@section('title', 'Quản lý trợ giảng')
@section('page-heading', 'Danh sách trợ giảng')

@php
    $active = 'tro-giang';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Quản lý trợ giảng</h2>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('admin.tro-giang.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition ml-2">
                    <i class="fas fa-user-plus mr-2"></i> Thêm trợ giảng
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('admin.tro-giang.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="q" id="search" value="{{ request('q') }}" placeholder="Tên, email, SĐT..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            
            <div>
                <label for="chuyen_mon" class="block text-sm font-medium text-gray-700 mb-1">Chuyên môn</label>
                <select id="chuyen_mon" name="chuyen_mon" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả chuyên môn</option>
                    <option value="hsk1" {{ request('chuyen_mon') == 'hsk1' ? 'selected' : '' }}>HSK 1</option>
                    <option value="hsk2" {{ request('chuyen_mon') == 'hsk2' ? 'selected' : '' }}>HSK 2</option>
                    <option value="hsk3" {{ request('chuyen_mon') == 'hsk3' ? 'selected' : '' }}>HSK 3</option>
                    <option value="hsk4" {{ request('chuyen_mon') == 'hsk4' ? 'selected' : '' }}>HSK 4</option>
                    <option value="hsk5" {{ request('chuyen_mon') == 'hsk5' ? 'selected' : '' }}>HSK 5</option>
                </select>
            </div>
            
            <div>
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="dang_lam_viec" {{ request('trang_thai') == 'dang_lam_viec' ? 'selected' : '' }}>Đang làm việc</option>
                    <option value="nghi_lam" {{ request('trang_thai') == 'nghi_lam' ? 'selected' : '' }}>Nghỉ làm</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.tro-giang.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách trợ giảng -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Danh sách trợ giảng ({{ $troGiangs->total() }})</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.tro-giang.index', array_merge(request()->query(), ['sort' => 'ten', 'direction' => request('direction') == 'asc' && request('sort') == 'ten' ? 'desc' : 'asc'])) }}" class="px-3 py-1 border border-gray-300 rounded-md text-sm {{ request('sort') == 'ten' ? 'bg-gray-100' : 'bg-white' }}">
                        Tên
                        @if(request('sort') == 'ten')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </a>
                    <a href="{{ route('admin.tro-giang.index', array_merge(request()->query(), ['sort' => 'so_nam_kinh_nghiem', 'direction' => request('direction') == 'asc' && request('sort') == 'so_nam_kinh_nghiem' ? 'desc' : 'asc'])) }}" class="px-3 py-1 border border-gray-300 rounded-md text-sm {{ request('sort') == 'so_nam_kinh_nghiem' ? 'bg-gray-100' : 'bg-white' }}">
                        Kinh nghiệm
                        @if(request('sort') == 'so_nam_kinh_nghiem')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin trợ giảng
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Chuyên môn
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lớp phụ trách
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin liên hệ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kinh nghiệm
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($troGiangs as $troGiang)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $troGiang->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-700">
                                        {{ strtoupper(substr($troGiang->nguoiDung->ho_ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $troGiang->nguoiDung->ho_ten }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Trợ giảng ID: {{ $troGiang->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $troGiang->chuyen_mon ?? '') as $chuyenMon)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ strtoupper($chuyenMon) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($troGiang->lopHocs->count() > 0)
                                    <div>
                                        <span class="font-medium">{{ $troGiang->lopHocs->count() }}</span> lớp
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $troGiang->lopHocs->first()->ten }}
                                        @if($troGiang->lopHocs->count() > 1)
                                            + {{ $troGiang->lopHocs->count() - 1 }} lớp khác
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">Chưa phân công</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $troGiang->nguoiDung->email }}
                                </div>
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $troGiang->nguoiDung->so_dien_thoai }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $troGiang->so_nam_kinh_nghiem }} năm
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.tro-giang.show', $troGiang->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tro-giang.edit', $troGiang->id) }}" class="text-green-600 hover:text-green-900" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tro-giang.destroy', $troGiang->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa trợ giảng này?');">
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
                                Không tìm thấy trợ giảng nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $troGiangs->appends(request()->query())->links() }}
        </div>
    </div>
@endsection 