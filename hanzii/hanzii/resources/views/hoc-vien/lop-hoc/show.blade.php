@extends('layouts.dashboard')

@section('title', 'Chi tiết lớp học')
@section('page-heading', $lopHoc->ten ?? 'Chi tiết lớp học')

@php
$active = 'lop-hoc';
$role = 'hoc_vien';
@endphp

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('hoc-vien.lop-hoc.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách
            </a>
        </div>

        <div class="flex space-x-2">
            @if($lopHoc->link_hoc_online)
            <a href="{{ $lopHoc->link_hoc_online }}" target="_blank"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Tham gia học trực tuyến
            </a>
            @endif

            <a href="{{ route('hoc-vien.lop-hoc.progress', $lopHoc->id) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Xem tiến độ học tập
            </a>
        </div>
    </div>
</div>

<!-- Thông tin lớp học -->
<div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Thông tin lớp học</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $lopHoc->ten }}</h3>
                    <p class="text-sm text-gray-600">Mã lớp: <span class="font-semibold">{{ $lopHoc->ma_lop }}</span>
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Trạng thái:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($lopHoc->trang_thai == 'dang_dien_ra') bg-green-100 text-green-800
                                @elseif($lopHoc->trang_thai == 'sap_khai_giang') bg-yellow-100 text-yellow-800
                                @elseif($lopHoc->trang_thai == 'da_hoan_thanh') bg-gray-100 text-gray-800
                                @else bg-blue-100 text-blue-800 @endif">
                            @if($lopHoc->trang_thai == 'dang_dien_ra') Đang diễn ra
                            @elseif($lopHoc->trang_thai == 'sap_khai_giang') Sắp khai giảng
                            @elseif($lopHoc->trang_thai == 'da_hoan_thanh') Đã hoàn thành
                            @else Đang hoạt động @endif
                        </span>
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Khóa học:</span>
                        {{ $lopHoc->khoaHoc->ten ?? 'Chưa có thông tin' }}
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Hình thức học:</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lopHoc->hinh_thuc == 'online' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $lopHoc->hinh_thuc == 'online' ? 'Trực tuyến' : 'Trực tiếp' }}
                        </span>
                    </p>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Giáo viên:</span>
                        {{ $lopHoc->giaoVien->nguoiDung->ho_ten ?? $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten ?? 'Chưa phân công' }}
                    </p>
                </div>

                @if($lopHoc->troGiang && $lopHoc->troGiang->nguoiDung)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Trợ giảng:</span>
                        {{ $lopHoc->troGiang->nguoiDung->ho_ten ?? $lopHoc->troGiang->nguoiDung->ho . ' ' . $lopHoc->troGiang->nguoiDung->ten ?? '' }}
                    </p>
                </div>
                @endif

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Thời gian:</span>
                        @if(isset($lopHoc->ngay_bat_dau) && isset($lopHoc->ngay_ket_thuc))
                        {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}
                        @else
                        Chưa xác định
                        @endif
                    </p>
                </div>

                @if($lopHoc->lich_hoc)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Lịch học:</span>
                        {{ $lopHoc->lich_hoc }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-4">
    <div class="flex border-b border-gray-200">
        <button onclick="openTab(event, 'tab-bai-hoc')"
            class="tab-link active py-4 px-6 text-center border-b-2 border-red-500 font-medium text-sm text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Bài học
        </button>
        <button onclick="openTab(event, 'tab-bai-tap')"
            class="tab-link py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Bài tập
        </button>
        <button onclick="openTab(event, 'tab-hoc-vien')"
            class="tab-link py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Học viên
        </button>
    </div>
</div>

<!-- Tab Contents -->
<!-- Bài học tab -->
<div id="tab-bai-hoc" class="tab-content block">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Danh sách bài học</h2>
        </div>

        @if($baiHocs->isEmpty())
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài học nào</h3>
            <p class="mt-1 text-sm text-gray-500">Giáo viên sẽ sớm cập nhật nội dung bài học.</p>
        </div>
        @else
        <ul class="divide-y divide-gray-200">
            @foreach($baiHocs as $baiHoc)
            <li class="flex items-center py-4 px-6 hover:bg-gray-50">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-red-600 font-semibold">{{ $baiHoc->so_thu_tu ?? $loop->iteration }}</span>
                </div>
                <div class="ml-4 flex-grow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-medium text-gray-900">
                                {{ $baiHoc->baiHoc->tieu_de ?? 'Bài học '.($loop->iteration) }}</h3>
                            <p class="text-sm text-gray-500">{{ Str::limit($baiHoc->baiHoc->mo_ta ?? '', 100) }}</p>
                            <div class="flex items-center mt-1 space-x-3">
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Thời lượng: {{ $baiHoc->baiHoc->thoi_luong ?? 45 }} phút
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Loại:
                                    @if(isset($baiHoc->baiHoc->loai) && $baiHoc->baiHoc->loai == 'video')
                                    Video
                                    @elseif(isset($baiHoc->baiHoc->loai) && $baiHoc->baiHoc->loai == 'bai_tap')
                                    Bài tập
                                    @else
                                    Văn bản
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if(isset($baiHoc->tien_do_hoc_tap) && $baiHoc->tien_do_hoc_tap->trang_thai ==
                            'da_hoan_thanh')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor"
                                    viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Đã hoàn thành
                            </span>
                            @elseif(isset($baiHoc->tien_do_hoc_tap) && $baiHoc->tien_do_hoc_tap->trang_thai ==
                            'dang_hoat_dong')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor"
                                    viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Đang diễn ra
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Chưa học
                            </span>
                            @endif

                            <a href="{{ route('hoc-vien.bai-hoc.show', [$lopHoc->id, $baiHoc->bai_hoc_id]) }}"
                                class="ml-4 inline-flex items-center px-3 py-1.5 border border-transparent rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Xem bài học
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

<!-- Bài tập tab -->
<div id="tab-bai-tap" class="tab-content hidden">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Danh sách bài tập</h2>
        </div>

        @if($baiTaps->isEmpty())
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài tập nào</h3>
            <p class="mt-1 text-sm text-gray-500">Giáo viên sẽ sớm cập nhật bài tập.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bài
                            tập</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn
                            nộp</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                            thái</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tùy
                            chọn</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($baiTaps as $baiTap)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $baiTap->tieu_de }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($baiTap->mo_ta ?? '', 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ isset($baiTap->han_nop) ? \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') : '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($baiTap->loai) && $baiTap->loai == 'tu_luan')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Tự
                                luận</span>
                            @elseif(isset($baiTap->loai) && $baiTap->loai == 'file')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">File</span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Bài
                                tập</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($baiTap->trang_thai == 'da_nop')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Đã
                                nộp</span>
                            @if(isset($baiTap->diem))
                            <span
                                class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Điểm:
                                {{ $baiTap->diem }}</span>
                            @endif
                            @elseif($baiTap->trang_thai == 'da_cham')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Đã
                                chấm</span>
                            @elseif($baiTap->trang_thai == 'qua_han')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Quá
                                hạn</span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Chưa
                                nộp</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($baiTap->trang_thai == 'da_nop' || $baiTap->trang_thai == 'da_cham')
                            <a href="{{ route('hoc-vien.bai-tap.show', $baiTap->id) }}"
                                class="text-blue-600 hover:text-blue-900">Xem chi tiết</a>
                            @elseif($baiTap->trang_thai == 'qua_han')
                            <span class="text-red-600">Đã hết hạn</span>
                            @else
                            <a href="{{ route('hoc-vien.bai-hoc.form-nop-bai-tap', [$lopHoc->id, $baiTap->bai_hoc_id, $baiTap->id]) }}"
                                class="text-green-600 hover:text-green-900">Làm bài</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Học viên tab -->
<div id="tab-hoc-vien" class="tab-content hidden">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Danh sách học viên trong lớp ({{ $danhSachHocVien->count() }})
            </h2>

        </div>

        @if($danhSachHocVien->isEmpty())
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có học viên nào</h3>
            <p class="mt-1 text-sm text-gray-500">Chưa có học viên nào tham gia lớp học này.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ và
                            tên</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số
                            điện thoại</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($danhSachHocVien as $index => $hv)
                    <tr class="{{ $hv->id == $hocVien->id ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-gray-500 font-medium">{{ substr($hv->nguoiDung->ho ?? '', 0, 1) . substr($hv->nguoiDung->ten ?? '', 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $hv->nguoiDung->ho ?? '' }} {{ $hv->nguoiDung->ten ?? '' }}
                                        @if($hv->id == $hocVien->id)
                                        <span
                                            class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Bạn</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $hv->nguoiDung->email ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $hv->nguoiDung->so_dien_thoai ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function openTab(evt, tabName) {
    var i, tabContent, tabLinks;

    // Hide all tab content
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    // Remove active class from all tab links
    tabLinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tabLinks.length; i++) {
        tabLinks[i].className = tabLinks[i].className.replace(" active", "");
        tabLinks[i].className = tabLinks[i].className.replace(" border-red-500", " border-transparent");
        tabLinks[i].className = tabLinks[i].className.replace(" text-red-600", " text-gray-500");
    }

    // Show current tab and add active class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    evt.currentTarget.className = evt.currentTarget.className.replace(" border-transparent", " border-red-500");
    evt.currentTarget.className = evt.currentTarget.className.replace(" text-gray-500", " text-red-600");
}
</script>
@endpush