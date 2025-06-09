@extends('layouts.admin')

@section('title', 'Quản lý thông báo lớp học')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách thông báo</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.thong-bao.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tạo thông báo mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.thong-bao.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Lớp học</label>
                                    <select name="lop_hoc_id" class="form-control select2">
                                        <option value="">-- Tất cả lớp học --</option>
                                        @foreach($lopHocs as $lopHoc)
                                            <option value="{{ $lopHoc->id }}" {{ request('lop_hoc_id') == $lopHoc->id ? 'selected' : '' }}>
                                                {{ $lopHoc->ten }} ({{ $lopHoc->khoaHoc->ten }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="trang_thai" class="form-control">
                                        <option value="">-- Tất cả --</option>
                                        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                                        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Không kích hoạt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Từ ngày</label>
                                    <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Đến ngày</label>
                                    <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tìm kiếm</label>
                                    <div class="input-group">
                                        <input type="text" name="q" class="form-control" placeholder="Tiêu đề, nội dung..." value="{{ request('q') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Người tạo</th>
                                    <th>Hiệu lực</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($thongBaos as $index => $thongBao)
                                    <tr>
                                        <td>{{ $thongBaos->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ route('admin.thong-bao.show', $thongBao->id) }}">{{ $thongBao->tieu_de }}</a>
                                            @if($thongBao->dinh_kem)
                                                <br><small><i class="fas fa-paperclip"></i> Có file đính kèm</small>
                                            @endif
                                        </td>
                                        <td>{{ $thongBao->lopHoc->ten }}</td>
                                        <td>
                                            {{ $thongBao->nguoiTao->ho_ten ?? 'N/A' }}
                                            <br>
                                            <small>{{ $thongBao->tao_luc->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <small>
                                                <strong>Bắt đầu:</strong> {{ $thongBao->ngay_hieu_luc ? $thongBao->ngay_hieu_luc->format('d/m/Y') : 'Ngay lập tức' }}
                                                <br>
                                                <strong>Kết thúc:</strong> {{ $thongBao->ngay_het_han ? $thongBao->ngay_het_han->format('d/m/Y') : 'Không có' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($thongBao->trang_thai == 1)
                                                <span class="badge badge-success">Kích hoạt</span>
                                            @else
                                                <span class="badge badge-secondary">Không kích hoạt</span>
                                            @endif

                                            @if($thongBao->daCoHieuLuc() && !$thongBao->daHetHan() && $thongBao->trang_thai == 1)
                                                <br><small class="text-success">Đang hiệu lực</small>
                                            @elseif($thongBao->daHetHan())
                                                <br><small class="text-danger">Đã hết hạn</small>
                                            @elseif(!$thongBao->daCoHieuLuc())
                                                <br><small class="text-warning">Chưa hiệu lực</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.thong-bao.show', $thongBao->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.thong-bao.edit', $thongBao->id) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.thong-bao.change-status', $thongBao->id) }}" class="btn btn-sm {{ $thongBao->trang_thai == 1 ? 'btn-warning' : 'btn-success' }}" title="{{ $thongBao->trang_thai == 1 ? 'Hủy kích hoạt' : 'Kích hoạt' }}">
                                                    <i class="fas {{ $thongBao->trang_thai == 1 ? 'fa-ban' : 'fa-check' }}"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-toggle="modal" data-target="#deleteModal" data-id="{{ $thongBao->id }}" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có thông báo nào</td>
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

<!-- Modal xóa thông báo -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa thông báo này không? Thao tác này không thể khôi phục.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
        
        // Xử lý modal xóa
        $('.btn-delete').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("admin.thong-bao.destroy", "") }}/' + id);
        });
    });
</script>
@endpush 