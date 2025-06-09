@extends('layouts.admin')

@section('title', 'Thống kê tổng quan')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Thống kê tổng quan</h1>
    
    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($tongHocVien) }}</h3>
                    <p>Tổng số học viên</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('admin.nguoi-dung.index', ['loai_tai_khoan' => 'hoc_vien']) }}" class="small-box-footer">
                    Chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
       
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($hocVienMoi) }}</h3>
                    <p>Học viên mới tháng này</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('admin.thong-ke.hoc-vien') }}" class="small-box-footer">
                    Chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lopHocDangDienRa }}</h3>
                    <p>Lớp học đang diễn ra</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('admin.lop-hoc.index', ['trang_thai' => 'dang_dien_ra']) }}" class="small-box-footer">
                    Chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thống kê doanh thu và chi phí -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Doanh thu và chi phí năm {{ date('Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="doanhThuChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <div class="description-block">
                                <h5 class="description-header text-success">{{ number_format(array_sum($dataDoanhThu)) }} VNĐ</h5>
                                <span class="description-text">TỔNG DOANH THU</span>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="description-block">
                                <h5 class="description-header text-danger">{{ number_format(array_sum($dataChiPhi)) }} VNĐ</h5>
                                <span class="description-text">TỔNG CHI PHÍ</span>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="description-block">
                                <h5 class="description-header text-primary">{{ number_format(array_sum($dataDoanhThu) - array_sum($dataChiPhi)) }} VNĐ</h5>
                                <span class="description-text">LỢI NHUẬN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Tổng quan nhân sự</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-users"></i> Tổng số học viên</td>
                                <td>{{ number_format($tongHocVien) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-chalkboard-teacher"></i> Tổng số giáo viên</td>
                                <td>{{ number_format($tongGiaoVien) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie"></i> Tổng số trợ giảng</td>
                                <td>{{ number_format($tongTroGiang) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-chalkboard"></i> Tổng số lớp học</td>
                                <td>{{ number_format($tongLopHoc) }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-book"></i> Tổng số khóa học</td>
                                <td>{{ number_format($tongKhoaHoc) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.thong-ke.hoc-vien') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-bar"></i> Thống kê học viên
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.lop-hoc.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list"></i> Danh sách lớp học
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê chi tiết -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-doanh-thu-tab" data-toggle="pill" href="#tab-doanh-thu" role="tab" aria-controls="tab-doanh-thu" aria-selected="true">Doanh thu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-chi-phi-tab" data-toggle="pill" href="#tab-chi-phi" role="tab" aria-controls="tab-chi-phi" aria-selected="false">Chi phí</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-loi-nhuan-tab" data-toggle="pill" href="#tab-loi-nhuan" role="tab" aria-controls="tab-loi-nhuan" aria-selected="false">Lợi nhuận</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="tab-doanh-thu" role="tabpanel" aria-labelledby="tab-doanh-thu-tab">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Doanh thu theo tháng năm {{ date('Y') }}</h5>
                                <div>
                                    <a href="{{ route('admin.thong-ke.doanh-thu-thang') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-chart-line"></i> Xem chi tiết theo tháng
                                    </a>
                                    <a href="{{ route('admin.thong-ke.doanh-thu-ngay') }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-calendar-day"></i> Xem chi tiết theo ngày
                                    </a>
                                </div>
                            </div>
                            <canvas id="doanhThuMonthlyChart" height="100"></canvas>
                        </div>
                       
                        <div class="tab-pane fade" id="tab-loi-nhuan" role="tabpanel" aria-labelledby="tab-loi-nhuan-tab">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Lợi nhuận theo tháng năm {{ date('Y') }}</h5>
                            </div>
                            <canvas id="loiNhuanMonthlyChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(function () {
        // Doanh thu và chi phí chart
        var doanhThuChartCanvas = document.getElementById('doanhThuChart').getContext('2d');
        var doanhThuChartData = {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [
                {
                    label: 'Doanh thu',
                    backgroundColor: 'rgba(60,141,188,0.3)',
                    borderColor: 'rgba(60,141,188,1)',
                    pointRadius: 3,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    borderWidth: 2,
                    fill: true,
                    data: @json($dataDoanhThu)
                },
                {
                    label: 'Chi phí',
                    backgroundColor: 'rgba(210, 214, 222, 0.3)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: 3,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    borderWidth: 2,
                    fill: true,
                    data: @json($dataChiPhi)
                }
            ]
        };

        var doanhThuChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + ' đ';
                    }
                }
            }
        };

        new Chart(doanhThuChartCanvas, {
            type: 'line',
            data: doanhThuChartData,
            options: doanhThuChartOptions
        });
        
        // Doanh thu monthly chart
        var doanhThuMonthlyChartCanvas = document.getElementById('doanhThuMonthlyChart').getContext('2d');
        var doanhThuMonthlyChartData = {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [
                {
                    label: 'Doanh thu',
                    backgroundColor: 'rgba(60,141,188,0.7)',
                    borderColor: 'rgba(60,141,188,1)',
                    pointRadius: 3,
                    borderWidth: 1,
                    data: @json($dataDoanhThu)
                }
            ]
        };

        var doanhThuMonthlyChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + ' đ';
                    }
                }
            }
        };

        new Chart(doanhThuMonthlyChartCanvas, {
            type: 'bar',
            data: doanhThuMonthlyChartData,
            options: doanhThuMonthlyChartOptions
        });
        
        // Chi phí monthly chart
        var chiPhiMonthlyChartCanvas = document.getElementById('chiPhiMonthlyChart').getContext('2d');
        var chiPhiMonthlyChartData = {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [
                {
                    label: 'Chi phí',
                    backgroundColor: 'rgba(210, 214, 222, 0.7)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: 3,
                    borderWidth: 1,
                    data: @json($dataChiPhi)
                }
            ]
        };

        var chiPhiMonthlyChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + ' đ';
                    }
                }
            }
        };

        new Chart(chiPhiMonthlyChartCanvas, {
            type: 'bar',
            data: chiPhiMonthlyChartData,
            options: chiPhiMonthlyChartOptions
        });
        
        // Lợi nhuận monthly chart
        var loiNhuanMonthlyChartCanvas = document.getElementById('loiNhuanMonthlyChart').getContext('2d');
        var loiNhuanMonthlyChartData = {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [
                {
                    label: 'Lợi nhuận',
                    backgroundColor: 'rgba(0, 192, 239, 0.7)',
                    borderColor: 'rgba(0, 192, 239, 1)',
                    pointRadius: 3,
                    borderWidth: 1,
                    data: @json($dataLoiNhuan)
                }
            ]
        };

        var loiNhuanMonthlyChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + ' đ';
                    }
                }
            }
        };

        new Chart(loiNhuanMonthlyChartCanvas, {
            type: 'bar',
            data: loiNhuanMonthlyChartData,
            options: loiNhuanMonthlyChartOptions
        });
    });
</script>
@endpush 