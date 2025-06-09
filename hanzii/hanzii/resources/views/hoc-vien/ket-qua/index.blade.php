@extends('layouts.dashboard')

@section('title', 'Kết quả học tập')
@section('page-heading', 'Kết quả học tập')

@php
$active = 'ket-qua';
$role = 'hoc_vien';
@endphp

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Kết quả học tập của {{ $hocVien->ho_ten }}</h2>
    </div>
</div>

<!-- Bộ lọc -->
<div class="bg-white shadow rounded-lg mb-6 p-4">
    <form action="{{ route('hoc-vien.ket-qua') }}" method="GET"
        class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
        <div class="flex-1">
            <label for="lop_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
            <select id="lop_hoc_id" name="lop_hoc_id"
                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="">Tất cả lớp học</option>
                @foreach($lopHocs as $lop)
                <option value="{{ $lop->id }}" {{ request('lop_hoc_id') == $lop->id ? 'selected' : '' }}>
                    {{ $lop->ten }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                Lọc kết quả
            </button>
        </div>
    </form>
</div>

<!-- Tổng quan kết quả -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-5 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Tổng quan kết quả học tập</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div
                    class="text-4xl font-bold {{ $diemTrungBinh >= 8 ? 'text-green-600' : ($diemTrungBinh >= 6.5 ? 'text-blue-600' : ($diemTrungBinh >= 5 ? 'text-yellow-600' : 'text-red-600')) }} mb-2">
                    {{ number_format($diemTrungBinh, 1) }}</div>
                <div class="text-sm text-gray-600">Điểm trung bình</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="text-4xl font-bold text-gray-700 mb-2">{{ $nopBaiTaps->total() }}</div>
                <div class="text-sm text-gray-600">Tổng số bài đã làm</div>
            </div>

        </div>
    </div>
</div>

<!-- Danh sách kết quả -->
<div class="bg-white rounded-lg shadow">
    <div
        class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
        <h3 class="text-lg font-medium text-gray-900">Chi tiết kết quả học tập</h3>

    </div>

    <!-- Bảng kết quả - phiên bản desktop -->
    <div class="hidden md:block overflow-x-auto">
        @if($nopBaiTaps->isNotEmpty())
        <table id="resultsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                        Tên bài
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        Lớp học
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                        Loại bài
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                        Ngày nộp
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                        Trạng thái
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                        Điểm
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($nopBaiTaps as $baiLam)
                <tr data-date="{{ $baiLam->created_at ?? $baiLam->ngay_nop }}" data-score="{{ $baiLam->diem ?? 0 }}">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs">
                            {{ $baiLam->baiTap->tieu_de ?? 'Không có tiêu đề' }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">
                            {{ $baiLam->baiTap->baiHoc->tieu_de ?? 'Không có bài học' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-900 truncate max-w-xs">
                            @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->baiHoc) &&
                            isset($baiLam->baiTap->baiHoc->baiHocLops))
                            @foreach($baiLam->baiTap->baiHoc->baiHocLops as $baiHocLop)
                            {{ $baiHocLop->lopHoc->ten ?? 'Không xác định' }}
                            @if(!$loop->last), @endif
                            @endforeach
                            @else
                            Không xác định
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->loai))
                                            @if($baiLam->baiTap->loai == 'tu_luan')
                                                bg-green-100 text-green-800
                                            @elseif($baiLam->baiTap->loai == 'file')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                            @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->loai))
                            @if($baiLam->baiTap->loai == 'tu_luan')
                            Tự luận
                            @elseif($baiLam->baiTap->loai == 'file')
                            Nộp file
                            @else
                            {{ ucfirst($baiLam->baiTap->loai) }}
                            @endif
                            @else
                            Không xác định
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($baiLam->ngay_nop ?? $baiLam->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($baiLam->trang_thai == 'da_cham')
                                            bg-green-100 text-green-800
                                        @elseif($baiLam->trang_thai == 'da_nop')
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                            @if($baiLam->trang_thai == 'da_cham')
                            Đã chấm
                            @elseif($baiLam->trang_thai == 'da_nop')
                            Đã nộp
                            @else
                            {{ ucfirst(str_replace('_', ' ', $baiLam->trang_thai ?? 'chưa_xác_định')) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if(isset($baiLam->diem))
                        <span
                            class="text-sm font-medium {{ $baiLam->diem >= 8 ? 'text-green-600' : 
                                                                     ($baiLam->diem >= 6.5 ? 'text-blue-600' : 
                                                                      ($baiLam->diem >= 5 ? 'text-yellow-600' : 'text-red-600')) }}">
                            {{ number_format($baiLam->diem, 1) }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500">Chưa chấm</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <a href="{{ route('hoc-vien.ket-qua.show', $baiLam->id) }}"
                            class="text-blue-600 hover:text-blue-900 font-medium">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $nopBaiTaps->links() }}
        </div>
        @else
        <div class="p-6 text-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-2 text-sm font-medium">Bạn chưa có kết quả học tập nào</p>
            <p class="mt-1 text-sm text-gray-500">Hoàn thành các bài tập được giao để xem kết quả</p>
        </div>
        @endif
    </div>

    <!-- Hiển thị trên điện thoại -->
    <div class="md:hidden">
        @if($nopBaiTaps->isNotEmpty())
        <div class="divide-y divide-gray-200">
            @foreach($nopBaiTaps as $baiLam)
            <div class="p-4" data-date="{{ $baiLam->created_at ?? $baiLam->ngay_nop }}"
                data-score="{{ $baiLam->diem ?? 0 }}">
                <div class="mb-3">
                    <h4 class="text-sm font-medium text-gray-900">{{ $baiLam->baiTap->tieu_de ?? 'Không có tiêu đề' }}
                    </h4>
                    <p class="text-xs text-gray-500">{{ $baiLam->baiTap->baiHoc->tieu_de ?? 'Không có bài học' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Lớp học:</span>
                        <span class="text-sm text-gray-900">
                            @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->baiHoc) &&
                            isset($baiLam->baiTap->baiHoc->baiHocLops))
                            {{ $baiLam->baiTap->baiHoc->baiHocLops[0]->lopHoc->ten ?? 'Không xác định' }}
                            @else
                            Không xác định
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500">Ngày nộp:</span>
                        <span class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($baiLam->ngay_nop ?? $baiLam->created_at)->format('d/m/Y') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500">Loại bài:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->loai))
                                            @if($baiLam->baiTap->loai == 'tu_luan')
                                                bg-green-100 text-green-800
                                            @elseif($baiLam->baiTap->loai == 'file')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                            @if(isset($baiLam->baiTap) && isset($baiLam->baiTap->loai))
                            @if($baiLam->baiTap->loai == 'tu_luan')
                            Tự luận
                            @elseif($baiLam->baiTap->loai == 'file')
                            Nộp file
                            @else
                            {{ ucfirst($baiLam->baiTap->loai) }}
                            @endif
                            @else
                            Không xác định
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500">Trạng thái:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($baiLam->trang_thai == 'da_cham')
                                            bg-green-100 text-green-800
                                        @elseif($baiLam->trang_thai == 'da_nop')
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                            @if($baiLam->trang_thai == 'da_cham')
                            Đã chấm
                            @elseif($baiLam->trang_thai == 'da_nop')
                            Đã nộp
                            @else
                            {{ ucfirst(str_replace('_', ' ', $baiLam->trang_thai ?? 'chưa_xác_định')) }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-3">
                    <div>
                        <span class="text-xs font-medium text-gray-500 mr-1">Điểm số:</span>
                        @if(isset($baiLam->diem))
                        <span
                            class="text-sm font-medium {{ $baiLam->diem >= 8 ? 'text-green-600' : 
                                                                    ($baiLam->diem >= 6.5 ? 'text-blue-600' : 
                                                                    ($baiLam->diem >= 5 ? 'text-yellow-600' : 'text-red-600')) }}">
                            {{ number_format($baiLam->diem, 1) }}/{{ $baiLam->baiTap->diem_toi_da ?? 10 }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500">Chưa chấm</span>
                        @endif
                    </div>

                    <a href="{{ route('hoc-vien.ket-qua.show', $baiLam->id) }}"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4">
            {{ $nopBaiTaps->links() }}
        </div>
        @else
        <div class="p-6 text-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-2 text-sm font-medium">Bạn chưa có kết quả học tập nào</p>
            <p class="mt-1 text-sm text-gray-500">Hoàn thành các bài tập được giao để xem kết quả</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function sortTable() {
    const sortOption = document.getElementById('sortOption').value;
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Sort mobile cards
        const container = document.querySelector('.md\\:hidden .divide-y');
        if (!container) return;

        const items = Array.from(container.querySelectorAll('[data-date]'));

        items.sort((a, b) => {
            if (sortOption === 'newest') {
                const dateA = new Date(a.dataset.date);
                const dateB = new Date(b.dataset.date);
                return dateB - dateA;
            } else if (sortOption === 'oldest') {
                const dateA = new Date(a.dataset.date);
                const dateB = new Date(b.dataset.date);
                return dateA - dateB;
            } else if (sortOption === 'highest') {
                return parseFloat(b.dataset.score) - parseFloat(a.dataset.score);
            } else if (sortOption === 'lowest') {
                return parseFloat(a.dataset.score) - parseFloat(b.dataset.score);
            }
            return 0;
        });

        items.forEach(item => container.appendChild(item));
    } else {
        // Sort table rows
        const table = document.getElementById('resultsTable');
        if (!table) return;

        const rows = Array.from(table.querySelectorAll('tbody tr'));

        rows.sort((a, b) => {
            if (sortOption === 'newest') {
                const dateA = new Date(a.dataset.date);
                const dateB = new Date(b.dataset.date);
                return dateB - dateA;
            } else if (sortOption === 'oldest') {
                const dateA = new Date(a.dataset.date);
                const dateB = new Date(b.dataset.date);
                return dateA - dateB;
            } else if (sortOption === 'highest') {
                return parseFloat(b.dataset.score) - parseFloat(a.dataset.score);
            } else if (sortOption === 'lowest') {
                return parseFloat(a.dataset.score) - parseFloat(b.dataset.score);
            }
            return 0;
        });

        const tbody = table.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row));
    }
}

// Hiển thị "Đang tính..." cho số bài chưa làm trong khi chờ tính toán
document.addEventListener('DOMContentLoaded', function() {
    const chuaLamElement = document.getElementById('chuaLam');
    if (chuaLamElement) {
        chuaLamElement.textContent = "Đang tính...";

        // Giả lập tính toán số bài chưa làm (thay thế bằng API gọi thực tế)
        setTimeout(() => {
            // Đây là nơi bạn sẽ thay thế bằng số thực từ API 
            // (số bài cần làm - số bài đã làm)
            chuaLamElement.textContent = "0";
        }, 1000);
    }
});
</script>
@endsection