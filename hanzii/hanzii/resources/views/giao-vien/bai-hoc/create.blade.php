@extends('layouts.dashboard')

@section('title', 'Tạo bài học mới')
@section('page-heading', 'Tạo bài học mới')

@php
    $active = 'bai-hoc';
    $role = 'giao_vien';
@endphp

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('giao-vien.bai-hoc.index', ['lop_hoc_id' => $lopHocId]) }}" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách bài học
            </a>
        </div>
            
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Thành công!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Lỗi!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Lỗi!</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tạo bài học mới</h3>
            </div>
            
            <form method="POST" action="{{ route('giao-vien.bai-hoc.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lop_hoc_id" value="{{ $lopHocId }}">

                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="tieu_de" class="block text-sm font-medium text-gray-700">
                                    Tiêu đề bài học <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="tieu_de" id="tieu_de" value="{{ old('tieu_de') }}" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <label for="thu_tu" class="block text-sm font-medium text-gray-700">
                                    Thứ tự <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="thu_tu" id="thu_tu" value="{{ old('thu_tu') }}" min="1" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="thoi_luong" class="block text-sm font-medium text-gray-700">
                                    Thời lượng (phút) <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="thoi_luong" id="thoi_luong" value="{{ old('thoi_luong') }}" min="1" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="loai" class="block text-sm font-medium text-gray-700">
                                    Loại bài học <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select id="loai" name="loai" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="toggleLoaiBaiHoc(this.value)">
                                        <option value="">-- Chọn loại bài học --</option>
                                        <option value="video" {{ old('loai') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="van_ban" {{ old('loai') == 'van_ban' ? 'selected' : '' }}>Văn bản</option>
                                    </select>
                                </div>
                            </div>


                            <div id="video_url_container" class="sm:col-span-6 {{ old('loai') != 'video' ? 'hidden' : '' }}">
                                <label for="url_video" class="block text-sm font-medium text-gray-700">
                                    URL Video (YouTube, Vimeo)
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="url_video" id="url_video" value="{{ old('url_video') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Nhập URL video từ YouTube hoặc Vimeo. Ví dụ: https://www.youtube.com/watch?v=XXXX
                                </p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="noi_dung" class="block text-sm font-medium text-gray-700">
                                    Nội dung bài học <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="noi_dung" name="noi_dung" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" rows="10">{{ old('noi_dung') }}</textarea>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="files" class="block text-sm font-medium text-gray-700">
                                    Tài liệu đính kèm
                                </label>
                                @error('files')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                                <div class="mt-1 flex items-center" id="file_container">
                                    <div class="space-y-2 w-full" id="file_inputs">
                                        <div class="flex items-center space-x-2">
                                            <input type="file" name="files[]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
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
                                <p class="mt-2 text-sm text-gray-500">
                                    Đính kèm tài liệu có định dạng: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR (tối đa 2MB mỗi file)
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('giao-vien.bai-hoc.index', ['lop_hoc_id' => $lopHocId]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-2">
                            Hủy
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tạo bài học
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
                // Endpoint ảnh sẽ được upload đến
                uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                
                // Thông tin headers gửi kèm mỗi request
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
    
    // Kiểm tra kích thước file (tối đa 2MB)
    function checkFileSize(fileInput) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        const file = fileInput.files[0];
        
        if (file && file.size > maxSize) {
            alert('Tệp "' + file.name + '" quá lớn. Kích thước tối đa cho phép là 2MB.');
            fileInput.value = ''; // Xóa file đã chọn
            return false;
        }
        
        return true;
    }
    
    // Xử lý thêm file
    document.getElementById('add_file').addEventListener('click', function() {
        const fileInputs = document.getElementById('file_inputs');
        const newFileInput = document.createElement('div');
        newFileInput.className = 'flex items-center space-x-2';
        newFileInput.innerHTML = `
            <input type="file" name="files[]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
            <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 delete-file">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        fileInputs.appendChild(newFileInput);
        
        // Thêm event listener cho input file mới
        const newInput = newFileInput.querySelector('input[type="file"]');
        newInput.addEventListener('change', function() {
            checkFileSize(this);
        });
        
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
                if (document.querySelectorAll('#file_inputs > div').length > 1) {
                    parentDiv.remove();
                    
                    // Nếu chỉ còn 1 input file, ẩn nút xóa
                    if (document.querySelectorAll('#file_inputs > div').length === 1) {
                        document.querySelector('.delete-file').classList.add('hidden');
                    }
                } else {
                    // Nếu chỉ còn 1 input file, xóa giá trị của nó
                    parentDiv.querySelector('input[type="file"]').value = '';
                }
            });
        });
    }
    
    // Thêm sự kiện xóa cho nút đầu tiên
    addDeleteFileEvent();
    
    // Thêm sự kiện kiểm tra kích thước file cho input file đầu tiên
    document.querySelector('input[name="files[]"]').addEventListener('change', function() {
        checkFileSize(this);
    });
    
    // Kiểm tra form trước khi submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const allFiles = document.querySelectorAll('input[name="files[]"]');
        let hasInvalidFiles = false;
        
        allFiles.forEach(fileInput => {
            if (fileInput.files.length > 0 && !checkFileSize(fileInput)) {
                hasInvalidFiles = true;
            }
        });
        
        if (hasInvalidFiles) {
            e.preventDefault();
            alert('Vui lòng xóa các tệp vượt quá kích thước cho phép trước khi gửi.');
        }
    });
</script>
@endpush 