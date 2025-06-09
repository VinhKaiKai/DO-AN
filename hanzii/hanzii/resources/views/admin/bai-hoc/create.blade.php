@extends('layouts.dashboard')

@section('title', 'Thêm bài học mới')
@section('page-heading', 'Thêm bài học mới')

@php
    $active = 'khoa-hoc';
    $role = 'admin';
@endphp

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

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

        <form action="{{ route('admin.bai-hoc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="tieu_de" class="block text-sm font-medium text-gray-700 mb-1">Tên bài học <span class="text-red-500">*</span></label>
                    <input type="text" name="tieu_de" id="tieu_de" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('tieu_de') }}" required>
                </div>
            
                
                
                <div>
                    <label for="so_thu_tu" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự <span class="text-red-500">*</span></label>
                    <input type="number" name="so_thu_tu" id="so_thu_tu" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('so_thu_tu') }}" required>
                </div>
                
                <div>
                    <label for="thoi_luong" class="block text-sm font-medium text-gray-700 mb-1">Thời lượng (phút) <span class="text-red-500">*</span></label>
                    <input type="number" name="thoi_luong" id="thoi_luong" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('thoi_luong') }}" required>
                </div>
                
                <div>
                    <label for="loai" class="block text-sm font-medium text-gray-700 mb-1">Loại bài học <span class="text-red-500">*</span></label>
                    <select name="loai" id="loai" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required onchange="toggleLoaiBaiHoc(this.value)">
                        <option value="video" {{ old('loai') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="van_ban" {{ old('loai') == 'van_ban' ? 'selected' : '' }}>Văn bản</option>
                    </select>
                </div>
                
                <div>
                    <label for="trang_thai" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="trang_thai" id="trang_thai" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="chua_xuat_ban" {{ old('trang_thai') == 'chua_xuat_ban' ? 'selected' : '' }}>Chưa xuất bản</option>
                        <option value="da_xuat_ban" {{ old('trang_thai') == 'da_xuat_ban' ? 'selected' : '' }}>Đã xuất bản</option>
                    </select>
                </div>
                
                <div class="col-span-2" id="video_url_container">
                    <label for="url_video" class="block text-sm font-medium text-gray-700 mb-1">URL Video (nếu có)</label>
                    <input type="url" name="url_video" id="url_video" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('url_video') }}">
                    <p class="mt-2 text-sm text-gray-500">
                        Nhập URL video từ YouTube hoặc Vimeo. Ví dụ: https://www.youtube.com/watch?v=XXXX
                    </p>
                </div>
                
                <div class="col-span-2">
                    <label for="noi_dung" class="block text-sm font-medium text-gray-700 mb-1">Nội dung bài học <span class="text-red-500">*</span></label>
                    <textarea name="noi_dung" id="noi_dung" rows="8" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('noi_dung') }}</textarea>
                </div>
                
                <div class="col-span-2">
                    <label for="tai_lieu" class="block text-sm font-medium text-gray-700 mb-1">Tài liệu bổ trợ</label>
                    <div class="mt-1 flex items-center" id="file_container">
                        <div class="space-y-2 w-full" id="file_inputs">
                            <div class="flex items-center space-x-2">
                                <input type="file" name="tai_lieu[]" class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-600
                                    hover:file:bg-indigo-100">
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 delete-file hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add_file" class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Thêm tài liệu
                    </button>
                    <p class="mt-1 text-xs text-gray-500">Định dạng được hỗ trợ: PDF, DOCX, XLSX, ZIP, RAR (Kích thước tối đa: 10MB)</p>
                </div>
                <input type="hidden" name="khoa_hoc_id" value="{{ $khoaHocId }}">

            </div>
            
            <div class="flex justify-between mt-8">
                <a href="{{ route('admin.bai-hoc.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Tạo bài học
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
<script>
    // Khởi tạo CKEditor cho trường nội dung
    ClassicEditor
        .create(document.querySelector('#noi_dung'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
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

    function toggleLoaiBaiHoc(loai) {
        const videoUrlContainer = document.getElementById('video_url_container');
        
        if (loai === 'video') {
            videoUrlContainer.classList.remove('hidden');
        } else {
            videoUrlContainer.classList.add('hidden');
        }
    }
    
    // Xử lý thêm file
    document.getElementById('add_file').addEventListener('click', function() {
        const fileInputs = document.getElementById('file_inputs');
        const newFileInput = document.createElement('div');
        newFileInput.className = 'flex items-center space-x-2';
        newFileInput.innerHTML = `
            <input type="file" name="tai_lieu[]" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-600
                hover:file:bg-indigo-100">
            <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 delete-file">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        fileInputs.appendChild(newFileInput);
        
        // Hiển thị nút xóa cho tất cả các input file
        const firstDeleteButton = document.querySelector('.delete-file');
        if (firstDeleteButton) {
            firstDeleteButton.classList.remove('hidden');
        }
        
        // Thêm sự kiện xóa cho nút mới
        addDeleteFileEvent();
    });
    
    // Xử lý xóa file
    function addDeleteFileEvent() {
        document.querySelectorAll('.delete-file').forEach(button => {
            button.addEventListener('click', function() {
                const parentDiv = this.parentElement;
                parentDiv.remove();
                
                // Nếu chỉ còn 1 input file, ẩn nút xóa
                const fileInputs = document.querySelectorAll('#file_inputs > div');
                if (fileInputs.length === 1) {
                    const lastDeleteButton = fileInputs[0].querySelector('.delete-file');
                    if (lastDeleteButton) {
                        lastDeleteButton.classList.add('hidden');
                    }
                }
            });
        });
    }
    
    // Khởi tạo sự kiện xóa file
    addDeleteFileEvent();
</script>
@endpush 