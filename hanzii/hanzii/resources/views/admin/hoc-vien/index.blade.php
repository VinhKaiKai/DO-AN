@extends('layouts.dashboard')

@section('title', 'Quản lý học viên')
@section('page-heading', 'Danh sách học viên')

@php
    $active = 'hoc-vien';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Quản lý học viên</h2>
          
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('admin.hoc-vien.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Tên, email, SĐT..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
            </div>
            
            <div>
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="dang_hoc" {{ request('trang_thai') == 'dang_hoc' ? 'selected' : '' }}>Đang học</option>
                    <option value="da_tot_nghiep" {{ request('trang_thai') == 'da_tot_nghiep' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                    <option value="ngung_hoc" {{ request('trang_thai') == 'ngung_hoc' ? 'selected' : '' }}>Ngừng học</option>
                </select>
            </div>
            
            <div>
                <label for="lop_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
                <select id="lop_hoc_id" name="lop_hoc_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả lớp học</option>
                    @foreach($lopHocs as $lopHoc)
                        <option value="{{ $lopHoc->id }}" {{ request('lop_hoc_id') == $lopHoc->id ? 'selected' : '' }}>
                            {{ $lopHoc->ten }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.hoc-vien.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Danh sách học viên -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Tất cả học viên ({{ $hocViens->total() }})</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.hoc-vien.index', array_merge(request()->query(), ['sort' => 'ho_ten', 'direction' => request('direction') == 'asc' && request('sort') == 'ho_ten' ? 'desc' : 'asc'])) }}" class="px-3 py-1 border border-gray-300 rounded-md text-sm {{ request('sort') == 'ho_ten' ? 'bg-gray-100' : 'bg-white' }}">
                        Tên
                        @if(request('sort') == 'ho_ten')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </a>
                    <a href="{{ route('admin.hoc-vien.index', array_merge(request()->query(), ['sort' => 'tao_luc', 'direction' => request('direction') == 'asc' && request('sort') == 'tao_luc' ? 'desc' : 'asc'])) }}" class="px-3 py-1 border border-gray-300 rounded-md text-sm {{ request('sort') == 'tao_luc' ? 'bg-gray-100' : 'bg-white' }}">
                        Ngày đăng ký
                        @if(request('sort') == 'tao_luc')
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
                            Thông tin học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lớp học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin liên hệ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày đăng ký
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hocViens as $hocVien)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($hocVien->ho_ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $hocVien->ho_ten }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Mã HV: {{ $hocVien->ma_hoc_vien }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($hocVien->lopHocs->count() > 0)
                                    <div class="text-sm text-gray-900">
                                        {{ $hocVien->lopHocs->first()->ten }}
                                    </div>
                                    @if($hocVien->lopHocs->count() > 1)
                                        <div class="text-xs text-gray-500">
                                            +{{ $hocVien->lopHocs->count() - 1 }} lớp khác
                                        </div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500">Chưa có lớp</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $hocVien->email }}
                                </div>
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i> {{ $hocVien->dien_thoai }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($hocVien->trang_thai == 'dang_hoc')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đang học
                                    </span>
                                @elseif($hocVien->trang_thai == 'da_tot_nghiep')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Đã tốt nghiệp
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Ngừng học
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($hocVien->tao_luc)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.hoc-vien.show', $hocVien->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hoc-vien.edit', $hocVien->id) }}" class="text-green-600 hover:text-green-900" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.hoc-vien.destroy', $hocVien->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa học viên này?');">
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
                                Không tìm thấy học viên nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $hocViens->appends(request()->query())->links() }}
        </div>
    </div>
    
    <!-- Thẻ thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Tổng số đang học</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $soLuongDangHoc }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-user-check text-lg"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Số học viên tốt nghiệp</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $soLuongTotNghiep }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-user-plus text-lg"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-sm font-medium text-gray-500">Học viên mới tháng này</h3>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $soLuongMoiThangNay }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection 