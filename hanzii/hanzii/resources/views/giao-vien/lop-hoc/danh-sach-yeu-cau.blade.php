@extends('layouts.giao-vien')

@section('title', 'Danh sách yêu cầu tham gia lớp học')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Danh sách yêu cầu tham gia lớp {{ $lopHoc->ten }}</h1>
        <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại lớp học
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Thống kê số lượng -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số yêu cầu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tongSo }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chờ duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $choDuyet }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đã duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $daDuyet }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Đã từ chối</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $daHuy }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">
                Chờ duyệt <span class="badge badge-warning">{{ $choDuyet }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">
                Đã duyệt <span class="badge badge-success">{{ $daDuyet }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                Đã từ chối <span class="badge badge-danger">{{ $daHuy }}</span>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- Chờ duyệt Tab -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yêu cầu chờ duyệt</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="pendingTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Lý do</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($yeuCauThamGias['cho_xac_nhan']) && count($yeuCauThamGias['cho_xac_nhan']) > 0)
                                    @foreach($yeuCauThamGias['cho_xac_nhan'] as $key => $yeuCau)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($yeuCau->tao_luc)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $yeuCau->ly_do ?? 'Không có' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('giao-vien.lop-hoc.xu-ly-yeu-cau-tham-gia', ['id' => $lopHoc->id, 'yeuCauId' => $yeuCau->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Chấp nhận">
                                                        <i class="fas fa-check"></i> Duyệt
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm ml-1" title="Từ chối" 
                                                        data-toggle="modal" data-target="#rejectModal{{ $yeuCau->id }}">
                                                    <i class="fas fa-times"></i> Từ chối
                                                </button>
                                            </div>

                                            <!-- Modal Từ chối -->
                                            <div class="modal fade" id="rejectModal{{ $yeuCau->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $yeuCau->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalLabel{{ $yeuCau->id }}">Từ chối yêu cầu tham gia</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('giao-vien.lop-hoc.xu-ly-yeu-cau-tham-gia', ['id' => $lopHoc->id, 'yeuCauId' => $yeuCau->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="action" value="reject">
                                                                <div class="form-group">
                                                                    <label for="ly_do">Lý do từ chối:</label>
                                                                    <textarea class="form-control" id="ly_do" name="ly_do" rows="3" placeholder="Nhập lý do từ chối (nếu có)"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Không có yêu cầu nào đang chờ duyệt</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đã duyệt Tab -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yêu cầu đã duyệt</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="approvedTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Ngày duyệt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($yeuCauThamGias['da_duyet']) && count($yeuCauThamGias['da_duyet']) > 0)
                                    @foreach($yeuCauThamGias['da_duyet'] as $key => $yeuCau)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($yeuCau->tao_luc)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($yeuCau->xu_ly_luc)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">Không có yêu cầu nào đã được duyệt</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đã từ chối Tab -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yêu cầu đã từ chối</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="rejectedTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Ngày từ chối</th>
                                    <th>Lý do từ chối</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($yeuCauThamGias['da_huy']) && count($yeuCauThamGias['da_huy']) > 0)
                                    @foreach($yeuCauThamGias['da_huy'] as $key => $yeuCau)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->ho_ten }}</td>
                                        <td>{{ $yeuCau->hocVien->nguoiDung->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($yeuCau->tao_luc)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($yeuCau->xu_ly_luc)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $yeuCau->ly_do_tu_choi ?? 'Không có lý do' }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Không có yêu cầu nào đã bị từ chối</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#pendingTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                "info": "Trang _PAGE_ / _PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ tổng số dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối cùng",
                    "next": "Tiếp theo",
                    "previous": "Trước đó"
                }
            }
        });
        
        $('#approvedTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                "info": "Trang _PAGE_ / _PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ tổng số dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối cùng",
                    "next": "Tiếp theo",
                    "previous": "Trước đó"
                }
            }
        });
        
        $('#rejectedTable').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                "info": "Trang _PAGE_ / _PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ tổng số dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối cùng",
                    "next": "Tiếp theo",
                    "previous": "Trước đó"
                }
            }
        });
    });
</script>
@endsection 