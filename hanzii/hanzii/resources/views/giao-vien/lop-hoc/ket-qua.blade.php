@extends('layouts.dashboard')

@section('title', 'Kết quả học tập lớp ' . $lopHoc->ten)
@section('page-heading', 'Kết quả học tập lớp ' . $lopHoc->ten)

@php
    $active = 'lop-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kết quả học tập lớp {{ $lopHoc->ten }}</h2>
                <p class="mt-1 text-sm text-gray-600">Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex">
                <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700 disabled:opacity-25 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông tin lớp học -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin lớp học</h3>
                <p class="mt-1 text-sm text-gray-900">Mã lớp: {{ $lopHoc->ma_lop }}</p>
                <p class="mt-1 text-sm text-gray-900">Hình thức: {{ $lopHoc->hinh_thuc_hoc == 'online' ? 'Trực tuyến' : 'Tại trung tâm' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thông tin giảng dạy</h3>
                <p class="mt-1 text-sm text-gray-900">Giáo viên: {{ $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten }}</p>
                <p class="mt-1 text-sm text-gray-900">Trợ giảng: {{ $lopHoc->troGiang->nguoiDung->ho . ' ' . $lopHoc->troGiang->nguoiDung->ten }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Thời gian học</h3>
                <p class="mt-1 text-sm text-gray-900">Bắt đầu: {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }}</p>
                <p class="mt-1 text-sm text-gray-900">Kết thúc: {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Tổng số học viên</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ count($ketQuaHocTaps) }}</p>
                </div>
            </div>
        </div>
        
        @php
            $diemTrungBinhLop = 0;
            $soHocVienCoDiem = 0;
            $tongTienDo = 0;
            
            foreach ($ketQuaHocTaps as $ketQua) {
                $tongTienDo += $ketQua['tien_do'];
                
                if ($ketQua['diem_trung_binh'] !== null) {
                    $diemTrungBinhLop += $ketQua['diem_trung_binh'];
                    $soHocVienCoDiem++;
                }
            }
            
            $diemTrungBinhLop = $soHocVienCoDiem > 0 ? round($diemTrungBinhLop / $soHocVienCoDiem, 1) : 0;
            $tienDoTrungBinh = count($ketQuaHocTaps) > 0 ? round($tongTienDo / count($ketQuaHocTaps), 1) : 0;
        @endphp
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Điểm trung bình lớp</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $diemTrungBinhLop }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Tiến độ trung bình</h3>
                    <p class="text-xl font-semibold text-gray-800">{{ $tienDoTrungBinh }}%</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Học viên hoàn thành</h3>
                    @php
                        $soHocVienHoanThanh = 0;
                        foreach ($ketQuaHocTaps as $ketQua) {
                            if ($ketQua['tien_do'] >= 80) {
                                $soHocVienHoanThanh++;
                            }
                        }
                        $phanTramHoanThanh = count($ketQuaHocTaps) > 0 ? round(($soHocVienHoanThanh / count($ketQuaHocTaps)) * 100) : 0;
                    @endphp
                    <p class="text-xl font-semibold text-gray-800">{{ $phanTramHoanThanh }}%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Danh sách kết quả học tập -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="ketQuaTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thông tin học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tiến độ học tập
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số bài đã nộp
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Điểm trung bình
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
                    @forelse($ketQuaHocTaps as $index => $ketQua)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($ketQua['nguoi_dung']->ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $ketQua['nguoi_dung']->ho . ' ' . $ketQua['nguoi_dung']->ten }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $ketQua['nguoi_dung']->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $ketQua['tien_do'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $ketQua['tien_do'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ketQua['so_bai_da_nop'] }} / {{ $ketQua['so_bai_da_cham'] }} bài đã chấm
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ketQua['diem_trung_binh'] !== null)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $ketQua['diem_trung_binh'] >= 8 ? 'bg-green-100 text-green-800' : 
                                        ($ketQua['diem_trung_binh'] >= 6.5 ? 'bg-blue-100 text-blue-800' : 
                                        ($ketQua['diem_trung_binh'] >= 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $ketQua['diem_trung_binh'] }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">Chưa có điểm</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $trangThai = '';
                                    $trangThaiClass = '';
                                    
                                    if ($ketQua['tien_do'] >= 80) {
                                        $trangThai = 'Hoàn thành';
                                        $trangThaiClass = 'bg-green-100 text-green-800';
                                    } elseif ($ketQua['tien_do'] >= 50) {
                                        $trangThai = 'Đang học';
                                        $trangThaiClass = 'bg-blue-100 text-blue-800';
                                    } elseif ($ketQua['tien_do'] > 0) {
                                        $trangThai = 'Mới bắt đầu';
                                        $trangThaiClass = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $trangThai = 'Chưa bắt đầu';
                                        $trangThaiClass = 'bg-gray-100 text-gray-800';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $trangThaiClass }}">
                                    {{ $trangThai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('giao-vien.hoc-vien.show', $ketQua['hoc_vien']->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có dữ liệu về kết quả học tập
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function printTable() {
        let printContents = document.getElementById('ketQuaTable').outerHTML;
        let originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
                <head>
                    <title>Kết quả học tập lớp {{ $lopHoc->ten }}</title>
                    <style>
                        table { border-collapse: collapse; width: 100%; }
                        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    <h1>Kết quả học tập lớp {{ $lopHoc->ten }}</h1>
                    <p>Khóa học: {{ $lopHoc->khoaHoc->ten }}</p>
                    <p>Ngày in: ${new Date().toLocaleDateString('vi-VN')}</p>
                    ${printContents}
                </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endpush 