@extends('layouts.dashboard')

@section('title', 'Quản Lý Tài Liệu')

@section('page_heading', 'Quản Lý Tài Liệu')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.tai-lieu.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <i class="fas fa-plus mr-2"></i>Thêm tài liệu mới
            </a>
        </div>
        <div class="flex space-x-2">
            <form action="{{ route('admin.tai-lieu.index') }}" method="GET" class="flex items-center">
                <div class="relative mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tài liệu..." class="w-64 pr-10 pl-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500">
                    @if(request('search'))
                        <a href="{{ route('admin.tai-lieu.index') }}" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <select id="bai_hoc_filter" name="bai_hoc_id" class="w-48 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500" onchange="this.form.action='{{ route('admin.tai-lieu.index') }}'; this.form.submit();">
                <option value="">Tất cả bài học</option>
                @foreach($baiHocs as $baiHoc)
                    <option value="{{ $baiHoc->id }}" {{ request('bai_hoc_id') == $baiHoc->id ? 'selected' : '' }}>
                        {{ $baiHoc->ten_bai_hoc }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-xs overflow-hidden">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($taiLieus->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">STT</th>
                            <th class="px-4 py-3">Tên tài liệu</th>
                            <th class="px-4 py-3">Bài học</th>
                            <th class="px-4 py-3">Lớp học</th>
                            <th class="px-4 py-3">Loại tệp</th>
                            <th class="px-4 py-3">Kích thước</th>
                            <th class="px-4 py-3">Ngày tạo</th>
                            <th class="px-4 py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @foreach($taiLieus as $index => $taiLieu)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{ $taiLieus->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium">
                                    {{ $taiLieu->ten_tai_lieu }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $taiLieu->baiHoc->ten_bai_hoc }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $taiLieu->baiHoc->lopHoc->ten_lop_hoc }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $taiLieu->getFileTypeClass() }}">
                                        {{ strtoupper($taiLieu->loai_file) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ number_format($taiLieu->kich_thuoc / 1024, 2) }} KB
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $taiLieu->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.tai-lieu.edit', $taiLieu->id) }}" class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.tai-lieu.download', $taiLieu->id) }}" class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-md hover:bg-green-200" title="Tải xuống">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.tai-lieu.destroy', $taiLieu->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200" title="Xóa">
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

            <div class="px-4 py-3 border-t">
                {{ $taiLieus->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-4 py-8 text-center">
                <p class="text-gray-500">Không tìm thấy tài liệu nào.</p>
                <p class="mt-2">
                    <a href="{{ route('admin.tai-lieu.create') }}" class="text-blue-600 hover:underline">
                        <i class="fas fa-plus mr-1"></i>Thêm tài liệu mới
                    </a>
                </p>
            </div>
        @endif
    </div>
@endsection 