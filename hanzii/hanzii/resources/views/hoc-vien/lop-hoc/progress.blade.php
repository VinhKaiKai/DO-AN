@extends('layouts.dashboard')

@section('title', 'Tiến độ học tập - ' . $lopHoc->ten)
@section('page-heading', 'Tiến độ học tập - ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <a href="{{ route('hoc-vien.lop-hoc.show', $lopHoc->id) }}" 
                    class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Quay lại lớp học
                </a>
                <h2 class="mt-2 text-xl font-semibold text-gray-800">{{ $lopHoc->ten }} - Khóa học: {{ $lopHoc->khoaHoc->ten }}</h2>
            </div>
            <div class="mt-3 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $lopHoc->trang_thai == 'dang_dien_ra' ? 'bg-green-100 text-green-800' : 
                       ($lopHoc->trang_thai == 'sap_khai_giang' ? 'bg-yellow-100 text-yellow-800' : 
                        'bg-gray-100 text-gray-800') }}">
                    @if($lopHoc->trang_thai == 'dang_dien_ra')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Đang diễn ra
                    @elseif($lopHoc->trang_thai == 'sap_khai_giang')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Sắp khai giảng
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Đã hoàn thành
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Tiến độ tổng quan -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tiến độ tổng quan</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center mb-6">
                <div class="md:w-2/3 mb-4 md:mb-0 md:pr-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Tiến độ hoàn thành</span>
                        <span class="text-sm font-medium text-red-600">{{ round($progressPercentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        Đã hoàn thành {{ $completedLessons }}/{{ $totalLessons }} bài học
                    </div>
                </div>
                
                <div class="md:w-1/3 flex flex-col items-center justify-center bg-gray-50 rounded-lg p-4">
                    <div class="text-3xl font-bold text-gray-700">{{ $completedLessons }}/{{ $totalLessons }}</div>
                    <div class="text-sm text-gray-600">Số bài học đã hoàn thành</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách bài học -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Chi tiết tiến độ theo bài học</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tên bài học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bài tập
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Điểm trung bình
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($baiHocs as $index => $baiHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $baiHoc->baiHoc->ten }}</div>
                                <div class="text-xs text-gray-500">
                                    Thời lượng: {{ $baiHoc->baiHoc->thoi_luong ?? 0 }} phút
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($baiHoc->tienDo) && $baiHoc->tienDo->first() && $baiHoc->tienDo->first()->trang_thai == 'da_hoan_thanh')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Đã hoàn thành
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ isset($baiHoc->tienDo) && $baiHoc->tienDo->first() && $baiHoc->tienDo->first()->ngay_hoan_thanh ? 
                                        \Carbon\Carbon::parse($baiHoc->tienDo->first()->ngay_hoan_thanh)->format('d/m/Y') : '' }}
                                    </div>
                                @elseif(isset($baiHoc->tienDo) && $baiHoc->tienDo->first())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Đang học
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Bắt đầu: {{ isset($baiHoc->tienDo) && $baiHoc->tienDo->first() && $baiHoc->tienDo->first()->ngay_bat_dau ? 
                                        \Carbon\Carbon::parse($baiHoc->tienDo->first()->ngay_bat_dau)->format('d/m/Y') : '' }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Chưa bắt đầu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $baiHoc->completed_bai_tap ?? 0 }}/{{ $baiHoc->total_bai_tap ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(isset($baiHoc->diem_trung_binh))
                                    <span class="font-medium {{ $baiHoc->diem_trung_binh >= 8 ? 'text-green-600' : 
                                                           ($baiHoc->diem_trung_binh >= 6.5 ? 'text-blue-600' : 
                                                            ($baiHoc->diem_trung_binh >= 5 ? 'text-yellow-600' : 'text-red-600')) }}">
                                        {{ number_format($baiHoc->diem_trung_binh, 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thông tin thêm -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin hữu ích</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Làm thế nào để học hiệu quả?</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Hoàn thành đầy đủ các bài học theo thứ tự</li>
                    <li>Làm bài tập ngay sau khi học xong bài</li>
                    <li>Dành thời gian ôn tập trước khi chuyển sang bài mới</li>
                    <li>Đặt câu hỏi với giáo viên khi gặp khó khăn</li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Cần hỗ trợ?</h4>
                <p class="text-sm text-gray-600 mb-2">
                    Nếu bạn cần trợ giúp về việc học hoặc làm bài tập, vui lòng liên hệ:
                </p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>
                        <span class="font-medium">Giáo viên:</span> 
                        {{ $lopHoc->giaoVien->nguoiDung->ho ?? '' }} {{ $lopHoc->giaoVien->nguoiDung->ten ?? '' }}
                    </li>
                    @if($lopHoc->troGiang && $lopHoc->troGiang->nguoiDung)
                        <li>
                            <span class="font-medium">Trợ giảng:</span> 
                            {{ $lopHoc->troGiang->nguoiDung->ho ?? '' }} {{ $lopHoc->troGiang->nguoiDung->ten ?? '' }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection 