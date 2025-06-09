@extends('layouts.dashboard')

@section('title', 'Danh sách bài tập')
@section('page-heading', 'Danh sách bài tập')

@php
$active = 'bai-tap';
$role = 'giao_vien';
@endphp

@section('content')
<div class="mb-6">
    <div class="flex items-center mb-4">
        <a href="{{ route('giao-vien.bai-hoc.show', ['bai_hoc' => request()->get('bai_hoc_id')]) }}"
            class="text-red-600 hover:text-red-800 mr-2">
            <i class="fas fa-arrow-left"></i> Quay lại bài học
        </a>
    </div>

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

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-tasks mr-2"></i> Danh sách bài tập
                        {{ isset($baiHoc) ? 'cho ' . $baiHoc->tieu_de : '' }}
                    </h3>
                    @if(isset($lopHoc))
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Lớp học: {{ $lopHoc->ten }} ({{ $lopHoc->ma_lop }})
                    </p>
                    @endif
                </div>
                <div>
                    @if(isset($baiHoc))
                    <a href="{{ route('giao-vien.bai-tap.create', ['bai_hoc_id' => $baiHoc->id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tạo bài tập mới
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($baiHoc) && isset($baiTaps) && $baiTaps->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <ul class="divide-y divide-gray-200">
            @foreach($baiTaps as $baiTap)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('giao-vien.bai-tap.show', $baiTap->id) }}"
                                        class="hover:text-red-600">
                                        {{ $baiTap->tieu_de }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    @if($baiTap->loai == 'tu_luan')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Tự luận
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        File
                                    </span>
                                    @endif

                                    <span class="ml-4">Điểm tối đa: {{ $baiTap->diem_toi_da }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col text-right">
                            <div class="text-sm text-gray-500">
                                Hạn nộp: {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                            </div>
                            <div class="flex mt-2">
                                <a href="{{ route('giao-vien.bai-tap.show', $baiTap->id) }}"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-2">
                                    <i class="fas fa-eye mr-1"></i> Xem
                                </a>
                                @if(!$baiTap->baiTapDaNops->count())
                                <a href="{{ route('giao-vien.bai-tap.edit', $baiTap->id) }}"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                                    <i class="fas fa-edit mr-1"></i> Sửa
                                </a>

                                <button type="button"
                                    onclick="showDeleteModal({{ $baiTap->id }}, '{{ $baiTap->tieu_de }}')"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash-alt mr-1"></i> Xóa
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $baiTaps->links() }}
        </div>
    </div>
    @elseif(isset($baiHoc))

    @endif
</div>
@endsection

<!-- Modal xác nhận xóa bài tập -->
<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Xác nhận xóa bài tập
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Bạn có chắc chắn muốn xóa bài tập "<span id="deleteItemName"></span>"? Hành động này
                                không thể hoàn tác.
                            </p>
                            <p class="mt-2 text-sm text-red-500">
                                Lưu ý: Tất cả dữ liệu liên quan đến bài tập này sẽ bị xóa vĩnh viễn.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Xác nhận xóa
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDeleteModal(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `{{ route('giao-vien.bai-tap.index') }}/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Đóng modal khi click bên ngoài
window.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
});

// Đóng modal khi nhấn ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush