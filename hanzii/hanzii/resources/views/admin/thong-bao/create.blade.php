@extends('layouts.admin')

@section('title', 'Tạo thông báo mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tạo thông báo mới</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.thong-bao.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.thong-bao.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="tieu_de">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" id="tieu_de" name="tieu_de" value="{{ old('tieu_de') }}" required>
                                    @error('tieu_de')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="lop_hoc_id">Lớp học <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('lop_hoc_id') is-invalid @enderror" id="lop_hoc_id" name="lop_hoc_id" required>
                                        <option value="">-- Chọn lớp học --</option>
                                        @foreach($lopHocs as $lopHoc)
                                            <option value="{{ $lopHoc->id }}" {{ old('lop_hoc_id') == $lopHoc->id ? 'selected' : '' }}>
                                                {{ $lopHoc->ten }} ({{ $lopHoc->khoaHoc->ten }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lop_hoc_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="noi_dung">Nội dung <span class="text-danger">*</span></label>
                                    <textarea class="form-control sumernote @error('noi_dung') is-invalid @enderror" id="noi_dung" name="noi_dung" rows="10" required>{{ old('noi_dung') }}</textarea>
                                    @error('noi_dung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="dinh_kem">File đính kèm</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('dinh_kem') is-invalid @enderror" id="dinh_kem" name="dinh_kem">
                                        <label class="custom-file-label" for="dinh_kem">Chọn file</label>
                                    </div>
                                    <small class="form-text text-muted">Tối đa 10MB. Chấp nhận các định dạng: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, JPG, PNG, GIF.</small>
                                    @error('dinh_kem')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">Thiết lập thông báo</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="ngay_hieu_luc">Ngày có hiệu lực</label>
                                            <input type="datetime-local" class="form-control @error('ngay_hieu_luc') is-invalid @enderror" id="ngay_hieu_luc" name="ngay_hieu_luc" value="{{ old('ngay_hieu_luc') }}">
                                            <small class="form-text text-muted">Để trống nếu muốn thông báo có hiệu lực ngay lập tức.</small>
                                            @error('ngay_hieu_luc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="ngay_het_han">Ngày hết hạn</label>
                                            <input type="datetime-local" class="form-control @error('ngay_het_han') is-invalid @enderror" id="ngay_het_han" name="ngay_het_han" value="{{ old('ngay_het_han') }}">
                                            <small class="form-text text-muted">Để trống nếu thông báo không có thời hạn.</small>
                                            @error('ngay_het_han')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="trang_thai">Trạng thái</label>
                                            <select class="form-control @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai">
                                                <option value="1" {{ old('trang_thai', '1') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                                                <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Không kích hoạt</option>
                                            </select>
                                            @error('trang_thai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-info-circle"></i> Thông báo sẽ hiển thị cho học viên của lớp khi:
                                            <ul class="mb-0 mt-2">
                                                <li>Trạng thái được kích hoạt</li>
                                                <li>Thời gian hiện tại nằm trong khoảng hiệu lực</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thông báo
                            </button>
                            <a href="{{ route('admin.thong-bao.index') }}" class="btn btn-default">
                                <i class="fas fa-times"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Initialize Summernote
        $('.sumernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Initialize Custom File Input
        bsCustomFileInput.init();
    });
</script>
@endpush 