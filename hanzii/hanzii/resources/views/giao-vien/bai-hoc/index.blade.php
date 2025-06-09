@extends('layouts.dashboard')

@section('title', 'Danh sách bài học')
@section('page-heading', 'Danh sách bài học')

@php
    $active = 'bai-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách bài học lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('giao-vien.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Danh sách lớp
                </a>
                <a href="{{ route('giao-vien.bai-hoc.create', ['lop_hoc_id' => $lopHoc->id]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-plus mr-2"></i> Thêm bài học mới
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông báo -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Thành công!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Lỗi!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Thông tin lớp học -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin lớp học</h3>
                <p class="mt-1 text-sm text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</p>
                <p class="mt-1 text-sm text-gray-900">Hình thức: {{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Lịch học</h3>
                <p class="mt-1 text-sm text-gray-900">Lịch học: {{ $lopHoc->lich_hoc }}</p>
                <p class="mt-1 text-sm text-gray-900">Thời gian: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Trạng thái</h3>
                <p class="mt-1 text-sm text-gray-900">
                    @php
                        $statusText = '';
                        
                        if ($lopHoc->ngay_bat_dau > now()) {
                            $statusText = 'Sắp khai giảng';
                        } elseif ($lopHoc->ngay_ket_thuc > now()) {
                            $statusText = 'Đang diễn ra';
                        } else {
                            $statusText = 'Đã kết thúc';
                        }
                    @endphp
                    {{ $statusText }}
                </p>
            </div>
        </div>
    </div>

    <!-- Danh sách bài học -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Danh sách bài học</h3>
            <a href="{{ route('giao-vien.bai-hoc.create', ['lop_hoc_id' => $lopHoc->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-plus mr-2"></i> Thêm bài học mới
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời lượng</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại bài học</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($baiHocs as $index => $baiHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $baiHoc->thu_tu }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('giao-vien.bai-hoc.show', $baiHoc->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    {{ $baiHoc->tieu_de }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $baiHoc->thoi_luong }} phút
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($baiHoc->loai == 'video')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Video
                                    </span>
                                @elseif($baiHoc->loai == 'van_ban')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Văn bản
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $baiHoc->loai }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('giao-vien.bai-hoc.show', $baiHoc->id) }}" class="bg-blue-100 text-blue-600 p-2 rounded-md hover:bg-blue-200" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('giao-vien.bai-hoc.edit', $baiHoc->id) }}" class="bg-yellow-100 text-yellow-600 p-2 rounded-md hover:bg-yellow-200" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="openDeleteModal('{{ $baiHoc->id }}', '{{ $baiHoc->tieu_de }}')" class="bg-red-100 text-red-600 p-2 rounded-md hover:bg-red-200" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 mb-3">Chưa có bài học nào cho lớp học này</p>
                                    <a href="{{ route('giao-vien.bai-hoc.create', ['lop_hoc_id' => $lopHoc->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Thêm bài học mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $baiHocs->appends(['lop_hoc_id' => $lopHoc->id])->links() }}
        </div>
    </div>

    <!-- Modal xóa bài học -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Xác nhận xóa bài học
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="delete-message">
                                    Bạn có chắc chắn muốn xóa bài học này? Hành động này không thể hoàn tác.
                                </p>
                                <p class="mt-2 text-sm text-red-500">
                                    Lưu ý: Tất cả dữ liệu liên quan đến bài học này như bài tập, tài liệu, kết quả học tập sẽ bị xóa vĩnh viễn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Xác nhận xóa
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openDeleteModal(id, title) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('delete-message').textContent = `Bạn có chắc chắn muốn xóa bài học "${title}"? Hành động này không thể hoàn tác.`;
        document.getElementById('deleteForm').action = `/giao-vien/bai-hoc/${id}?redirect_lop_hoc_id={{ $lopHoc->id }}`;
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush 