@extends('layouts.dashboard')

@section('title', 'Chi tiết bài tập')
@section('page-heading', 'Chi tiết bài tập')

@php
    $active = 'bai-tap';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="javascript:history.back();" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại
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

        <!-- Thông tin bài tập -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-clipboard-list mr-2"></i> {{ $baiTap->tieu_de }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Bài học: {{ $baiTap->baiHoc->tieu_de }}
                        </p>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Lớp học: {{ $lopHoc->ten }} ({{ $lopHoc->ma_lop }})
                        </p>
                    </div>
                    <div>
                        @if(!$baiTap->baiTapDaNops->count())
                            <a href="{{ route('giao-vien.bai-tap.edit', $baiTap->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-2">
                                <i class="fas fa-edit mr-2"></i> Chỉnh sửa
                            </a>
                        @endif
                        
                        <form action="{{ route('giao-vien.bai-tap.destroy', $baiTap->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài tập này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash-alt mr-2"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Loại bài tập</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($baiTap->loai == 'tu_luan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Tự luận
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    File
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Điểm tối đa</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $baiTap->diem_toi_da }} điểm</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Hạn nộp</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Mô tả</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $baiTap->noi_dung }}</dd>
                    </div>
                    @if($baiTap->file_dinh_kem)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">File đính kèm</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ asset('storage/' . $baiTap->file_dinh_kem) }}" target="_blank" class="inline-flex items-center text-red-600 hover:text-red-800">
                                    <i class="fas fa-file-download mr-1"></i> {{ $baiTap->ten_file ?: 'Tải xuống file đính kèm' }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Thống kê nộp bài -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-chart-bar mr-2"></i> Thống kê nộp bài
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Tổng số học viên</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $tongSoHocVien }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Đã nộp bài</p>
                            <p class="mt-1 text-3xl font-semibold text-green-600">{{ $daNop }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Đã chấm điểm</p>
                            <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $daCham }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Chưa nộp bài</p>
                            <p class="mt-1 text-3xl font-semibold text-red-600">{{ $chuaNop }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách bài đã nộp -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-users mr-2"></i> Danh sách học viên nộp bài
                </h3>
            </div>
            <div class="border-t border-gray-200">
                @if($baiTapDaNops->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học viên</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian nộp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($baiTapDaNops as $baiTapDaNop)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $baiTapDaNop->hocVien->nguoiDung->ho_ten }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $baiTapDaNop->hocVien->nguoiDung->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($baiTapDaNop->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($baiTapDaNop->trang_thai == 'da_nop')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Chờ chấm
                                                </span>
                                            @elseif($baiTapDaNop->trang_thai == 'da_cham')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Đã chấm
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $baiTapDaNop->trang_thai }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($baiTapDaNop->trang_thai == 'da_cham')
                                                <span class="font-medium">{{ $baiTapDaNop->diem }}/{{ $baiTap->diem_toi_da }}</span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($baiTap->loai == 'tu_luan')
                                                <a href="{{ route('giao-vien.cham-diem.tu-luan', $baiTapDaNop->id) }}" class="text-red-600 hover:text-red-800 mr-3">
                                                    <i class="fas fa-check-circle mr-1"></i> Chấm điểm
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $baiTapDaNop->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 mr-3">
                                                    <i class="fas fa-download mr-1"></i> Tải file
                                                </a>
                                                <a href="{{ route('giao-vien.cham-diem.tu-luan', $baiTapDaNop->id) }}" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Chấm điểm
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có học viên nộp bài</h3>
                        <p class="text-gray-600">Danh sách sẽ được cập nhật khi có học viên nộp bài tập.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection 