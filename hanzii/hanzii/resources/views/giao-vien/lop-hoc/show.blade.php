@extends('layouts.dashboard')

@section('title', 'Chi tiết lớp học')
@section('page-heading', $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <!-- Header với các nút điều hướng -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Chi tiết lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('giao-vien.lop-hoc.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Danh sách lớp
                </a>
                <a href="{{ route('giao-vien.bai-hoc.create', ['lop_hoc_id' => $lopHoc->id]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-plus mr-2"></i> Thêm bài học
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông tin lớp học dạng card -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Thông tin lớp học</h3>
            @php
                $statusClass = '';
                $statusText = '';
                
                if ($lopHoc->ngay_bat_dau > now()) {
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                    $statusText = 'Sắp khai giảng';
                } elseif ($lopHoc->ngay_ket_thuc > now()) {
                    $statusClass = 'bg-green-100 text-green-800';
                    $statusText = 'Đang diễn ra';
                } else {
                    $statusClass = 'bg-gray-100 text-gray-800';
                    $statusText = 'Đã kết thúc';
                }
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                {{ $statusText }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Thời gian</h4>
                <p class="text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-900 mt-1">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-900 mt-1">Số buổi: {{ $lopHoc->so_buoi }} buổi</p>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Lịch học & Địa điểm</h4>
                <p class="text-sm text-gray-900">Lịch học: {{ $lopHoc->lich_hoc }}</p>
                <p class="text-sm text-gray-900 mt-1">Hình thức: {{ $lopHoc->hinh_thuc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
                @if($lopHoc->hinh_thuc == 'online' && $lopHoc->link_meeting)
                    <a href="{{ $lopHoc->link_meeting }}" target="_blank" class="mt-1 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-video mr-1"></i> Link học trực tuyến
                    </a>
                @endif
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Tiến độ giảng dạy</h4>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                    <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $lopHoc->tienDo() }}%"></div>
                </div>
                <p class="text-sm text-gray-500">{{ $lopHoc->tienDo() }}% hoàn thành</p>
            </div>
        </div>
    </div>

    <!-- Công cụ quản lý lớp học -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-md bg-red-100 flex items-center justify-center">
                    <i class="fas fa-users text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Học viên</h3>
                    <p class="text-sm text-gray-500">Quản lý danh sách học viên</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('giao-vien.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="w-full block bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-users mr-2"></i> Xem danh sách
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-md bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Bài học</h3>
                    <p class="text-sm text-gray-500">Quản lý nội dung bài học</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('giao-vien.bai-hoc.index', ['lop_hoc_id' => $lopHoc->id]) }}" class="w-full block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-book-open mr-2"></i> Xem bài học
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-md bg-green-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Tiến độ & Kết quả</h3>
                    <p class="text-sm text-gray-500">Xem tiến độ và kết quả học tập</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('giao-vien.lop-hoc.ket-qua', $lopHoc->id) }}" class="w-full block bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i> Xem kết quả
                </a>
            </div>
        </div>
    </div>

    <!-- Danh sách bài học gần đây -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách bài học gần đây</h3>
            <a href="{{ route('giao-vien.bai-hoc.index', ['lop_hoc_id' => $lopHoc->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                Xem tất cả <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
        <div class="px-6 py-4">
            @forelse($baiHocs as $baiHoc)
                <div class="border-b border-gray-200 py-4 last:border-b-0 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($baiHoc->da_hoan_thanh)
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                            @else
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-book text-gray-600"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-base font-medium text-gray-900">{{ $baiHoc->ten }}</h4>
                                <p class="text-sm text-gray-500">
                                    <span class="mr-3"><i class="far fa-clock mr-1"></i> {{ $baiHoc->thoi_luong }} phút</span>
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $baiHoc->ngay_day ? \Carbon\Carbon::parse($baiHoc->ngay_day)->format('d/m/Y') : 'Chưa có' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('giao-vien.bai-hoc.show', $baiHoc->id) }}" class="inline-flex items-center py-1.5 px-3 rounded-md bg-blue-100 text-blue-700 text-sm hover:bg-blue-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i> Xem
                                </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-500">Chưa có bài học nào trong lớp này</p>
                    <a href="{{ route('giao-vien.bai-hoc.create', ['lop_hoc_id' => $lopHoc->id]) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-plus mr-2"></i> Thêm bài học mới
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = '{{ route('giao-vien.yeu-cau-tham-gia.tu-choi', '') }}/' + id;
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection 