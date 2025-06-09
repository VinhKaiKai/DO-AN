@extends('layouts.dashboard')

@section('title', 'Lịch giảng dạy')
@section('page-heading', 'Lịch giảng dạy')

@php
    $active = 'lich-day';
    $role = 'giao_vien';
    
    // Xác định ngày bắt đầu và kết thúc của tuần hiện tại
    $today = \Carbon\Carbon::now();
    $startOfWeek = $today->copy()->startOfWeek();
    $endOfWeek = $today->copy()->endOfWeek();
    
    // Tuần trước và tuần sau
    $previousWeek = $startOfWeek->copy()->subWeek()->startOfWeek();
    $nextWeek = $startOfWeek->copy()->addWeek()->startOfWeek();
    
    // Tạo mảng các ngày trong tuần
    $daysOfWeek = [];
    for ($i = 0; $i < 7; $i++) {
        $daysOfWeek[] = $startOfWeek->copy()->addDays($i);
    }
    
    // Tên các ngày trong tuần
    $dayNames = ['Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy', 'Chủ Nhật'];
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Lịch giảng dạy của tôi</h2>
                <p class="mt-1 text-sm text-gray-600">Hiển thị tất cả các lớp học và lịch trình giảng dạy hàng tuần</p>
            </div>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('giao-vien.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 mr-3 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
                <button onclick="printSchedule()" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                    <i class="fas fa-print mr-2"></i> In lịch
                </button>
            </div>
        </div>
    </div>
    
    <!-- Điều hướng tuần -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('giao-vien.lop-hoc.lich-day', ['week' => $previousWeek->format('Y-m-d')]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-arrow-left mr-2"></i> Tuần trước
            </a>
            
            <h3 class="text-lg font-medium text-gray-900">
                Tuần từ {{ $startOfWeek->format('d/m/Y') }} đến {{ $endOfWeek->format('d/m/Y') }}
            </h3>
            
            <a href="{{ route('giao-vien.lop-hoc.lich-day', ['week' => $nextWeek->format('Y-m-d')]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Tuần sau <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    
    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Tổng số lớp</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $lopHocs->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Tổng số học viên</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $tongSoHocVien }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Buổi học tuần này</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $soTietDayTrongTuan }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Giờ dạy tuần này</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $soGioDayTrongTuan }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lịch giảng dạy -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6" id="printable-schedule">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lịch giảng dạy hàng tuần</h3>
            <p class="mt-1 text-sm text-gray-600">Từ ngày {{ $startOfWeek->format('d/m/Y') }} đến ngày {{ $endOfWeek->format('d/m/Y') }}</p>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col" class="w-16 px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thời gian
                            </th>
                            @foreach($daysOfWeek as $index => $day)
                                <th scope="col" class="px-6 py-3 {{ $day->isToday() ? 'bg-red-50' : 'bg-gray-50' }} text-left text-xs font-medium {{ $day->isToday() ? 'text-red-500' : 'text-gray-500' }} uppercase tracking-wider">
                                    <div>{{ $dayNames[$index] }}</div>
                                    <div>{{ $day->format('d/m') }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(['07:00 - 09:00', '09:30 - 11:30', '13:30 - 15:30', '16:00 - 18:00', '18:30 - 20:30'] as $timeSlot)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">
                                    {{ $timeSlot }}
                                </td>
                                
                                @foreach($daysOfWeek as $day)
                                    <td class="px-6 py-4 {{ $day->isToday() ? 'bg-red-50' : '' }}">
                                        @php
                                            $scheduledClasses = [];
                                            foreach($lopHocs as $lopHoc) {
                                                $lichHoc = json_decode($lopHoc->lich_hoc, true);
                                                if (is_array($lichHoc)) {
                                                    foreach($lichHoc as $lich) {
                                                        if ($lich['thu'] == $day->dayOfWeek && $lich['gio'] == $timeSlot) {
                                                            $scheduledClasses[] = $lopHoc;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @if(count($scheduledClasses) > 0)
                                            @foreach($scheduledClasses as $class)
                                                <div class="mb-2 last:mb-0">
                                                    <a href="{{ route('giao-vien.lop-hoc.show', $class->id) }}" class="block p-3 bg-red-100 hover:bg-red-200 rounded-md border-l-4 border-red-600 transition">
                                                        <div class="font-medium text-red-900">{{ $class->ten }}</div>
                                                        <div class="text-xs mt-1 text-gray-600">
                                                            <div><i class="fas fa-book-reader mr-1"></i> {{ $class->khoaHoc->ten }}</div>
                                                            <div><i class="fas fa-map-marker-alt mr-1"></i> {{ $class->dia_diem ?: 'Trực tuyến' }}</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-sm text-gray-500 italic">Không có lớp</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Danh sách lớp học đang dạy -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách lớp học đang giảng dạy</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tên lớp
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khóa học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lopHocs as $lopHoc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($lopHoc->ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $lopHoc->ten }}</div>
                                        <div class="text-xs text-gray-500">Mã lớp: {{ $lopHoc->ma_lop }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lopHoc->khoaHoc->ten }}</div>
                                <div class="text-xs text-gray-500">{{ $lopHoc->khoaHoc->loai }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <div><i class="fas fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</div>
                                    <div class="mt-1"><i class="fas fa-clock mr-1"></i> {{ $lopHoc->lich_hoc_text }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lopHoc->so_hoc_vien }} / {{ $lopHoc->so_hoc_vien_toi_da }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    switch($lopHoc->trang_thai) {
                                        case 'dang_dien_ra':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Đang diễn ra';
                                            break;
                                        case 'sap_khai_giang':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'Sắp khai giảng';
                                            break;
                                        case 'da_ket_thuc':
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = 'Đã kết thúc';
                                            break;
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('giao-vien.lop-hoc.danh-sach-hoc-vien', $lopHoc->id) }}" class="text-green-600 hover:text-green-900" title="Danh sách học viên">
                                        <i class="fas fa-users"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Bạn chưa được phân công lớp học nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function printSchedule() {
        let printContents = document.getElementById('printable-schedule').outerHTML;
        let originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
                <head>
                    <title>Lịch giảng dạy - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        h2 { text-align: center; }
                    </style>
                </head>
                <body>
                    <h2>Lịch giảng dạy tuần {{ $startOfWeek->format('d/m/Y') }} - {{ $endOfWeek->format('d/m/Y') }}</h2>
                    <p>Giáo viên: {{ auth()->user()->ho . ' ' . auth()->user()->ten }}</p>
                    <p>Ngày in: ${new Date().toLocaleDateString('vi-VN')}</p>
                    ${printContents}
                </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endsection 