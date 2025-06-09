@extends('layouts.hoc-vien')

@section('title', 'Chi tiết thông báo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $thongBao->tieu_de }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('hoc-vien.thong-bao.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Thông tin thông báo</h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">Lớp học:</dt>
                                        <dd class="col-sm-8">{{ $thongBao->lopHoc->ten }}</dd>
                                        
                                        <dt class="col-sm-4">Khóa học:</dt>
                                        <dd class="col-sm-8">{{ $thongBao->lopHoc->khoaHoc->ten }}</dd>
                                        
                                        <dt class="col-sm-4">Đăng bởi:</dt>
                                        <dd class="col-sm-8">{{ $thongBao->nguoiTao->ho_ten }}</dd>
                                        
                                        <dt class="col-sm-4">Ngày đăng:</dt>
                                        <dd class="col-sm-8">{{ $thongBao->created_at->format('d/m/Y H:i') }}</dd>
                                        
                                        <dt class="col-sm-4">Hiệu lực:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-info">
                                                {{ $thongBao->ngay_hieu_luc ? $thongBao->ngay_hieu_luc->format('d/m/Y H:i') : 'Ngay lập tức' }}
                                            </span>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Hết hạn:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-warning">
                                                {{ $thongBao->ngay_het_han ? $thongBao->ngay_het_han->format('d/m/Y H:i') : 'Không giới hạn' }}
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($thongBao->dinh_kem)
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">File đính kèm</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <i class="fas fa-file fa-2x text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    @php
                                                        $fileName = basename($thongBao->dinh_kem);
                                                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                                                    @endphp
                                                    <strong>{{ $fileName }}</strong>
                                                </div>
                                                <small class="text-muted">
                                                    @if(in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <i class="fas fa-image"></i> Hình ảnh
                                                    @elseif(in_array(strtolower($fileExt), ['pdf']))
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    @elseif(in_array(strtolower($fileExt), ['doc', 'docx']))
                                                        <i class="fas fa-file-word"></i> Word
                                                    @elseif(in_array(strtolower($fileExt), ['xls', 'xlsx']))
                                                        <i class="fas fa-file-excel"></i> Excel
                                                    @elseif(in_array(strtolower($fileExt), ['ppt', 'pptx']))
                                                        <i class="fas fa-file-powerpoint"></i> PowerPoint
                                                    @elseif(in_array(strtolower($fileExt), ['zip', 'rar']))
                                                        <i class="fas fa-file-archive"></i> Nén
                                                    @else
                                                        <i class="fas fa-file"></i> File
                                                    @endif
                                                </small>
                                            </div>
                                            <div>
                                                <a href="{{ asset('storage/' . $thongBao->dinh_kem) }}" class="btn btn-primary btn-sm" download>
                                                    <i class="fas fa-download"></i> Tải xuống
                                                </a>
                                                
                                                @if(in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                                                    <a href="{{ asset('storage/' . $thongBao->dinh_kem) }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Nội dung thông báo</h3>
                        </div>
                        <div class="card-body">
                            <div class="content">
                                {!! $thongBao->noi_dung !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('hoc-vien.thong-bao.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('hoc-vien.lop-hoc.show', $thongBao->lopHoc->id) }}" class="btn btn-primary">
                            <i class="fas fa-chalkboard"></i> Đi đến lớp học
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content img {
        max-width: 100%;
        height: auto;
    }
    
    .content {
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .content table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        border-collapse: collapse;
    }
    
    .content table th,
    .content table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush 