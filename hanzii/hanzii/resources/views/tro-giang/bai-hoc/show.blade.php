@extends('layouts.dashboard')

@section('title', $baiHoc->ten ?? $baiHoc->tieu_de ?? 'Chi tiết bài học')
@section('page-heading', $baiHoc->ten ?? $baiHoc->tieu_de ?? 'Chi tiết bài học')

@php
    $active = 'lop-hoc';
    $role = 'tro_giang';
@endphp

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar - Danh mục bài học -->
    <div class="lg:w-1/4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-red-600 text-white px-4 py-3">
                <h3 class="font-medium">Danh sách bài học</h3>
            </div>
            <div class="p-2">
                <ul class="divide-y divide-gray-200">
                    @foreach($danhSachBaiHoc as $index => $baiHocItem)
                        @php
                            $current = $baiHocItem->bai_hoc_id == $baiHoc->id;
                        @endphp
                        <li class="relative">
                            <a href="{{ route('tro-giang.bai-hoc.show', ['lopHocId' => $lopHoc->id, 'baiHocId' => $baiHocItem->bai_hoc_id]) }}"
                               class="block px-4 py-3 {{ $current ? 'bg-red-50' : '' }} hover:bg-gray-50 relative">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($current)
                                            <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center text-white">
                                                <span class="text-xs font-medium">{{ $index + 1 }}</span>
                                            </div>
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center text-white">
                                                <span class="text-xs font-medium">{{ $index + 1 }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="truncate">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $baiHocItem->baiHoc->ten ?? $baiHocItem->baiHoc->tieu_de ?? "Bài " . ($index + 1) }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Học viên đã hoàn thành -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
            <div class="bg-green-600 text-white px-4 py-3">
                <h3 class="font-medium">Tiến độ học viên</h3>
            </div>
            <div class="p-4">
                <div class="text-sm text-gray-700 mb-3">
                    <p class="font-medium mb-1">Học viên đã hoàn thành:</p>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $totalStudents = count($hocViens);
                                $completedStudents = $tienDoBaiHocs->where('trang_thai', 'da_hoan_thanh')->count();
                                $percentComplete = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;
                            @endphp
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $percentComplete }}%"></div>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $percentComplete }}%</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ $completedStudents }}/{{ $totalStudents }} học viên</p>
                </div>
                
                <!-- Danh sách học viên -->
                <div class="mt-3">
                    <p class="font-medium text-sm mb-2">Trạng thái từng học viên:</p>
                    <ul class="divide-y divide-gray-200">
                        @foreach($hocViens as $dangKyHoc)
                            @php
                                $hocVien = $dangKyHoc->hocVien;
                                $daHoanThanh = isset($tienDoBaiHocs[$hocVien->id]) && $tienDoBaiHocs[$hocVien->id]->trang_thai === 'da_hoan_thanh';
                            @endphp
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full {{ $daHoanThanh ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                    <span class="text-sm">{{ $hocVien->nguoiDung->ho . ' ' . $hocVien->nguoiDung->ten }}</span>
                                </div>
                                <span class="text-xs {{ $daHoanThanh ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $daHoanThanh ? 'Đã hoàn thành' : 'Chưa hoàn thành' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="lg:w-3/4">
        <!-- Thông tin bài học -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $baiHoc->ten ?? $baiHoc->tieu_de ?? "Bài học" }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Thời lượng: {{ $baiHoc->thoi_luong ?? '45' }} phút
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Video nếu có -->
                @if($youtubeId)
                <div class="mb-6">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-lg"></iframe>
                    </div>
                </div>
                @endif
                
                <div class="prose max-w-none">
                    {!! $baiHoc->noi_dung ?? '<p class="text-gray-500">Không có nội dung chi tiết cho bài học này.</p>' !!}
                </div>
            </div>
        </div>

        <!-- Danh sách bài tập -->
        @if(isset($baiHoc->baiTaps) && count($baiHoc->baiTaps) > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900">Bài tập ({{ count($baiHoc->baiTaps) }})</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        @foreach($baiHoc->baiTaps as $baiTap)
                            @php
                                // Đếm số lượng bài đã nộp
                                $soLuongDaNop = $baiTap->baiTapDaNops->count();
                                $soLuongDaCham = $baiTap->baiTapDaNops->where('trang_thai', 'da_cham')->count();
                                
                                // Đảm bảo các trường hiển thị có dữ liệu
                                $tieuDe = $baiTap->ten ?? $baiTap->tieu_de ?? 'Bài tập không có tiêu đề';
                                $loaiBaiTap = $baiTap->loai ?? 'tu_luan';
                                $moTa = $baiTap->mo_ta ?? '';
                            @endphp
                            
                            <li class="border rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $tieuDe }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Loại bài tập:</span> 
                                                @if($loaiBaiTap == 'tu_luan')
                                                    Tự luận
                                                @elseif($loaiBaiTap == 'file')
                                                    Nộp file
                                                @else
                                                    {{ ucfirst($loaiBaiTap) }}
                                                @endif
                                            </p>
                                            @if($baiTap->han_nop)
                                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Hạn nộp: {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $soLuongDaNop }} đã nộp / {{ $soLuongDaCham }} đã chấm
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 border-t">
                                    @if($moTa)
                                    <div class="prose prose-sm max-w-none mb-4">
                                        {!! $moTa !!}
                                    </div>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ route('tro-giang.bai-tap.index', ['bai_tap_id' => $baiTap->id]) }}" 
                                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Xem bài đã nộp
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <p class="text-gray-600">Không có bài tập nào cho bài học này</p>
            </div>
        @endif
        
        <!-- Tài liệu bổ trợ -->
        @if(isset($baiHoc->taiLieuBoTros) && count($baiHoc->taiLieuBoTros) > 0)
            <div class="bg-white rounded-lg shadow mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900">Tài liệu bổ trợ</h3>
                </div>
                <div class="p-6">
                    <ul class="divide-y divide-gray-200">
                        @foreach($baiHoc->taiLieuBoTros as $taiLieu)
                            <li class="py-3 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $taiLieu->ten ?? 'Tài liệu' }}</span>
                                </div>
                                <a href="{{ route('tro-giang.tai-lieu.download', $taiLieu->id) }}" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Tải xuống
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Component bình luận -->
<x-binh-luan :binhLuans="$baiHoc->binhLuans" :baiHocId="$baiHoc->id" :lopHocId="$lopHoc->id" role="tro-giang" />
@endsection 