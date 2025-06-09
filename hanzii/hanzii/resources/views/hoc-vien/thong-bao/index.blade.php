@extends('layouts.hoc-vien')

@section('title', 'Thông báo lớp học')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông báo lớp học</h3>
                    <div class="card-tools">
                        <form action="{{ route('hoc-vien.thong-bao.mark-all-as-read') }}" method="POST" id="markAllAsReadForm">
                            @csrf
                            <input type="hidden" name="lop_hoc_id" value="{{ $lopHocId }}">
                            <button type="submit" class="btn btn-success btn-sm" id="markAllAsReadBtn">
                                <i class="fas fa-check-double"></i> Đánh dấu tất cả là đã đọc
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('hoc-vien.thong-bao.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Lớp học</label>
                                    <select name="lop_hoc_id" class="form-control select2" onchange="this.form.submit()">
                                        <option value="">-- Tất cả lớp học --</option>
                                        @foreach($lopHocs as $lop)
                                            <option value="{{ $lop->id }}" {{ $lopHocId == $lop->id ? 'selected' : '' }}>
                                                {{ $lop->ten }} ({{ $lop->khoaHoc->ten }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="da_doc" class="form-control" onchange="this.form.submit()">
                                        <option value="">-- Tất cả --</option>
                                        <option value="0" {{ request('da_doc') === '0' ? 'selected' : '' }}>Chưa đọc</option>
                                        <option value="1" {{ request('da_doc') === '1' ? 'selected' : '' }}>Đã đọc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Tìm kiếm</label>
                                    <div class="input-group">
                                        <input type="text" name="q" class="form-control" placeholder="Tiêu đề, nội dung..." value="{{ request('q') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Ngày đăng</th>
                                    <th style="width: 100px">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($thongBaos as $index => $thongBao)
                                    <tr class="{{ $thongBao->da_doc ? '' : 'font-weight-bold bg-light' }}">
                                        <td>{{ $thongBaos->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ route('hoc-vien.thong-bao.show', $thongBao->id) }}">
                                                {{ $thongBao->tieu_de }}
                                            </a>
                                            @if(!$thongBao->da_doc)
                                                <span class="badge badge-danger">Mới</span>
                                            @endif
                                            @if($thongBao->dinh_kem)
                                                <i class="fas fa-paperclip ml-1" title="Có file đính kèm"></i>
                                            @endif
                                        </td>
                                        <td>{{ $thongBao->lopHoc->ten }}</td>
                                        <td>{{ $thongBao->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($thongBao->da_doc)
                                                <span class="badge badge-success">Đã đọc</span>
                                            @else
                                                <span class="badge badge-warning">Chưa đọc</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có thông báo nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $thongBaos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Xác nhận khi đánh dấu tất cả là đã đọc
        $('#markAllAsReadBtn').click(function(e) {
            e.preventDefault();
            
            if (confirm('Bạn có chắc chắn muốn đánh dấu tất cả thông báo là đã đọc?')) {
                $('#markAllAsReadForm').submit();
            }
        });
    });
</script>
@endpush 