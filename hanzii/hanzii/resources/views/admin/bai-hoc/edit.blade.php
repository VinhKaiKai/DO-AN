@extends('layouts.dashboard')

@section('title', 'Chỉnh sửa bài học')
@section('page-heading', 'Chỉnh sửa bài học')

@php
    $active = 'bai-hoc';
    $role = 'admin';
@endphp

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.bai-hoc.update', $baiHoc->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tieu_de" class="block text-sm font-medium text-gray-700 mb-1">Tên bài học <span class="text-red-500">*</span></label>
                    <input type="text" name="tieu_de" id="tieu_de" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('tieu_de', $baiHoc->tieu_de) }}" required>
                </div>
                
                <div>
                    <label for="khoa_hoc_id" class="block text-sm font-medium text-gray-700 mb-1">Khóa học <span class="text-red-500">*</span></label>
                    <select name="khoa_hoc_id" id="khoa_hoc_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Chọn khóa học --</option>
                        @foreach($khoaHocs as $khoaHoc)
                            <option value="{{ $khoaHoc->id }}" {{ (old('khoa_hoc_id', $baiHoc->khoa_hoc_id) == $khoaHoc->id) ? 'selected' : '' }}>
                                {{ $khoaHoc->ten }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="so_thu_tu" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự <span class="text-red-500">*</span></label>
                    <input type="number" name="so_thu_tu" id="so_thu_tu" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('so_thu_tu', $baiHoc->so_thu_tu) }}" required>
                </div>
                
                <div>
                    <label for="thoi_luong" class="block text-sm font-medium text-gray-700 mb-1">Thời lượng (phút) <span class="text-red-500">*</span></label>
                    <input type="number" name="thoi_luong" id="thoi_luong" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('thoi_luong', $baiHoc->thoi_luong) }}" required>
                </div>
                
                <div>
                    <label for="loai" class="block text-sm font-medium text-gray-700 mb-1">Loại bài học <span class="text-red-500">*</span></label>
                    <select name="loai" id="loai" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="video" {{ (old('loai', $baiHoc->loai) == 'video') ? 'selected' : '' }}>Video</option>
                        <option value="van_ban" {{ (old('loai', $baiHoc->loai) == 'van_ban') ? 'selected' : '' }}>Văn bản</option>
                    </select>
                </div>
                
                <div>
                    <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="trang_thai" id="trang_thai" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="chua_xuat_ban" {{ (old('trang_thai', $baiHoc->trang_thai) == 'chua_xuat_ban') ? 'selected' : '' }}>Chưa xuất bản</option>
                        <option value="da_xuat_ban" {{ (old('trang_thai', $baiHoc->trang_thai) == 'da_xuat_ban') ? 'selected' : '' }}>Đã xuất bản</option>
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label for="url_video" class="block text-sm font-medium text-gray-700 mb-1">URL Video (nếu có)</label>
                    <input type="url" name="url_video" id="url_video" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('url_video', $baiHoc->url_video) }}">
                </div>
                
                <div class="col-span-2">
                    <label for="noi_dung" class="block text-sm font-medium text-gray-700 mb-1">Nội dung bài học <span class="text-red-500">*</span></label>
                    <textarea name="noi_dung" id="noi_dung" rows="8" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('noi_dung', $baiHoc->noi_dung) }}</textarea>
                </div>
                
                <div class="col-span-2">
                    <label for="tai_lieu" class="block text-sm font-medium text-gray-700 mb-1">Tài liệu bổ trợ</label>
                    <input type="file" name="tai_lieu[]" id="tai_lieu" multiple class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-600
                        hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Định dạng được hỗ trợ: PDF, DOCX, XLSX, ZIP, RAR (Kích thước tối đa: 10MB)</p>
                </div>
                
                @if($baiHoc->taiLieuBoTros->count() > 0)
                <div class="col-span-2 mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tài liệu hiện có</label>
                    <div class="bg-gray-50 p-4 rounded-md border">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($baiHoc->taiLieuBoTros as $taiLieu)
                            <div class="flex items-center justify-between p-2 bg-white rounded border">
                                <div class="flex items-center">
                                    <input type="checkbox" name="xoa_tai_lieu[]" id="xoa_tai_lieu_{{ $taiLieu->id }}" value="{{ $taiLieu->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="xoa_tai_lieu_{{ $taiLieu->id }}" class="ml-2 text-sm text-gray-700">{{ $taiLieu->tieu_de }}</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">
                                        @php
                                            try {
                                                if (Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
                                                    echo \App\Helpers\FileHelper::formatFileSize(Storage::disk('public')->size($taiLieu->duong_dan_file));
                                                } else {
                                                    echo 'File không tồn tại';
                                                }
                                            } catch (\Exception $e) {
                                                echo 'Không thể đọc kích thước';
                                            }
                                        @endphp
                                    </span>
                                    <a href="{{ route('admin.tai-lieu.download', $taiLieu->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800" target="_blank">
                                        <i class="fas fa-download"></i> Tải xuống
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-red-500">Đánh dấu vào ô để xóa tài liệu.</p>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="flex justify-between mt-8">
                <a href="{{ route('admin.bai-hoc.show', $baiHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#noi_dung'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'],
            simpleUpload: {
                uploadUrl: '{{ route("ckeditor.upload") }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection 