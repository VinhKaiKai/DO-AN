@extends('layouts.dashboard')

@section('title', 'Trang chủ học viên')
@section('page-heading', 'Trang chủ học viên')

@php
    $active = 'dashboard';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="container-fluid">
        <!-- Phần thông báo -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Phần Danh sách lớp học -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Danh sách lớp học đang tham gia</h5>
                    </div>
                    <div class="card-body">
                        @if($lopDangHoc->count() > 0)
                            <div class="row">
                                @foreach($lopDangHoc as $dangKy)
                                    @if($dangKy->lopHoc)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card h-100 border">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="card-title mb-0">{{ $dangKy->lopHoc->ten_lop }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Mã lớp:</strong> {{ $dangKy->lopHoc->ma_lop }}</p>
                                                    <p><strong>Khóa học:</strong> {{ $dangKy->lopHoc->khoaHoc->ten_khoa_hoc ?? 'Chưa xác định' }}</p>
                                                    <p><strong>Hình thức học:</strong> {{ $dangKy->lopHoc->hinh_thuc_hoc }}</p>
                                                    <p><strong>Thời gian:</strong> {{ $dangKy->lopHoc->thoi_gian_bat_dau ? date('d/m/Y', strtotime($dangKy->lopHoc->thoi_gian_bat_dau)) : 'Chưa xác định' }}</p>
                                                    <p><strong>Trạng thái lớp:</strong> <span class="badge badge-primary">{{ $dangKy->lopHoc->trang_thai_text }}</span></p>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="{{ route('hoc-vien.lop-hoc.show', $dangKy->lopHoc->id) }}" class="btn btn-primary btn-block">Xem chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <p class="mb-0">Bạn chưa tham gia lớp học nào. Vui lòng liên hệ với quản trị viên để được hỗ trợ.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 