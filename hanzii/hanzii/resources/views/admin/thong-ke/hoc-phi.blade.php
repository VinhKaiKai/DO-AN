@extends('layouts.dashboard')

@section('title', 'Thống kê học phí')
@section('page-heading', 'Thống kê học phí')

@php
    $active = 'thong-ke';
    $role = 'admin';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Thống kê học phí</h2>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('admin.hoc-phi.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition ml-2">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm học phí
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('admin.thong-ke.hoc-phi') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="thoi_gian" class="block text-sm font-medium text-gray-700 mb-1">Thời gian</label>
                <select id="thoi_gian" name="thoi_gian" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="thang_nay" {{ request('thoi_gian', 'thang_nay') == 'thang_nay' ? 'selected' : '' }}>Tháng này</option>
                    <option value="quy_nay" {{ request('thoi_gian') == 'quy_nay' ? 'selected' : '' }}>Quý này</option>
                    <option value="nam_nay" {{ request('thoi_gian') == 'nam_nay' ? 'selected' : '' }}>Năm nay</option>
                    <option value="tat_ca" {{ request('thoi_gian') == 'tat_ca' ? 'selected' : '' }}>Tất cả</option>
                </select>
            </div>
            
            <div>
                <label for="khoa_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Khóa học</label>
                <select id="khoa_hoc_id" name="khoa_hoc_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả khóa học</option>
                    @foreach($khoaHocs as $khoaHoc)
                        <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>
                            {{ $khoaHoc->ten }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    <option value="">Tất cả trạng thái</option>
                    <option value="da_thanh_toan" {{ request('trang_thai') == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="chua_thanh_toan" {{ request('trang_thai') == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="thanh_toan_mot_phan" {{ request('trang_thai') == 'thanh_toan_mot_phan' ? 'selected' : '' }}>Thanh toán một phần</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-filter mr-1"></i> Lọc
                </button>
                <a href="{{ route('admin.thong-ke.hoc-phi') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-md bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Tổng học phí
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ number_format($tongHocPhi, 0, ',', '.') }} đ
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-md bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Đã thanh toán
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ number_format($daThu, 0, ',', '.') }} đ
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-md bg-yellow-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Chưa thanh toán
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ number_format($chuaThu, 0, ',', '.') }} đ
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-md bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Số học viên
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $soHocVien }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Biểu đồ thu học phí theo tháng</h3>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Phân bổ học phí theo khóa học</h3>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="courseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách học phí -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Chi tiết học phí</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã HP
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khóa học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số tiền
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Đã thanh toán
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Còn lại
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày thanh toán
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
                    @forelse($hocPhis as $hocPhi)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                HP{{ str_pad($hocPhi->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($hocPhi->hocVien->ho_ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $hocPhi->hocVien->ho_ten }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $hocPhi->hocVien->ma_hoc_vien }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocPhi->khoaHoc->ten }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($hocPhi->so_tien, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                {{ number_format($hocPhi->da_thanh_toan, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($hocPhi->so_tien - $hocPhi->da_thanh_toan, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocPhi->ngay_thanh_toan ? \Carbon\Carbon::parse($hocPhi->ngay_thanh_toan)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    if ($hocPhi->trang_thai == 'da_thanh_toan') {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Đã thanh toán';
                                    } elseif ($hocPhi->trang_thai == 'thanh_toan_mot_phan') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Thanh toán một phần';
                                    } else {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Chưa thanh toán';
                                    }
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.hoc-phi.edit', $hocPhi->id) }}" class="text-green-600 hover:text-green-900" title="Cập nhật">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.hoc-phi.show', $hocPhi->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hoc-phi.in-hoa-don', $hocPhi->id) }}" class="text-indigo-600 hover:text-indigo-900" title="In hóa đơn">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không tìm thấy dữ liệu học phí nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $hocPhis->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Công nợ học phí học viên -->
    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách học viên công nợ nhiều nhất</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số điện thoại
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lớp học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tổng học phí
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Đã thanh toán
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Còn nợ
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hocVienCongNos as $hocVien)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($hocVien->ho_ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $hocVien->ho_ten }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->ma_hoc_vien }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->dien_thoai }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($hocVien->lopHocs->count() > 0)
                                    {{ $hocVien->lopHocs->first()->ten }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($hocVien->tong_hoc_phi, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                {{ number_format($hocVien->da_thanh_toan, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                {{ number_format($hocVien->cong_no, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.hoc-vien.show', $hocVien->id) }}" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hoc-phi.tao-nhac-nho', $hocVien->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Nhắc nhở">
                                        <i class="fas fa-bell"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có học viên nào đang nợ học phí.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Biểu đồ học phí theo tháng
        var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        var monthlyData = {!! json_encode($monthlyData) !!};
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(function(item) { return item.thang; }),
                datasets: [{
                    label: 'Học phí (triệu đồng)',
                    data: monthlyData.map(function(item) { return item.tong_tien / 1000000; }),
                    backgroundColor: 'rgba(234, 88, 12, 0.2)',
                    borderColor: 'rgba(234, 88, 12, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' tr';
                            }
                        }
                    }
                }
            }
        });
        
        // Biểu đồ phân bổ học phí theo khóa học
        var courseCtx = document.getElementById('courseChart').getContext('2d');
        var courseData = {!! json_encode($courseData) !!};
        
        new Chart(courseCtx, {
            type: 'pie',
            data: {
                labels: courseData.map(function(item) { return item.ten; }),
                datasets: [{
                    data: courseData.map(function(item) { return item.tong_tien; }),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(199, 199, 199, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var percentage = Math.round((value / total) * 100);
                                
                                return label + ': ' + new Intl.NumberFormat('vi-VN').format(value) + ' đ (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 