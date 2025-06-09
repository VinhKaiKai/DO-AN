@extends('layouts.dashboard')

@section('title', 'Nộp bài tập')
@section('page-heading', 'Nộp bài tập')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Card chính -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h5 class="text-lg font-medium text-gray-900">
                <i class="fas fa-pencil-alt mr-2 text-red-600"></i>{{ $baiTap->tieu_de }}
            </h5>
            <div>
                @if ($baiTap->han_nop)
                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hạn nộp: {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="p-6">
            <!-- Thông báo quan trọng -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <span class="font-medium">Lưu ý:</span> Hãy đọc kỹ yêu cầu của bài tập trước khi nộp bài. Bạn chỉ được nộp bài 1 lần duy nhất.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin bài tập -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                    <h6 class="text-sm font-medium text-gray-700 uppercase tracking-wider mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Thông tin bài tập
                    </h6>
                    
                    @if ($baiTap->noi_dung)
                        <div class="mb-4">
                            <h6 class="text-sm font-medium text-gray-700 mb-2">Mô tả bài tập:</h6>
                            <div class="p-4 bg-white rounded border border-gray-200 prose prose-sm max-w-none">
                                {!! $baiTap->noi_dung !!}
                            </div>
                        </div>
                    @endif
    
                    @if ($baiTap->file_dinh_kem)
                        <div>
                            <h6 class="text-sm font-medium text-gray-700 mb-2">File đính kèm:</h6>
                            <a href="{{ asset('storage/' . $baiTap->file_dinh_kem) }}" target="_blank" 
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Tải xuống {{ $baiTap->ten_file ?: 'File đính kèm' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Form nộp bài -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                    <h6 class="text-sm font-medium text-gray-700 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Nộp bài tập
                    </h6>
                </div>
                <div class="p-5">
                    <form action="{{ route('hoc-vien.bai-tap.nop-bai', $baiTap->id) }}" method="post" enctype="multipart/form-data" id="submitForm">
                        @csrf
                        <input type="hidden" name="is_new_submission" value="1">
                        
                        @if ($baiTap->loai == 'tu_luan')
                            <div class="mb-4">
                                <label for="noi_dung" class="block text-sm font-medium text-gray-700 mb-1">Nội dung bài làm:</label>
                                <textarea name="noi_dung" id="noi_dung" rows="10" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('noi_dung') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                          required>{{ old('noi_dung') }}</textarea>
                                @error('noi_dung')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Nhập nội dung bài làm của bạn vào khung trên
                                </p>
                            </div>
                        @else
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Tải lên file bài làm:</label>
                                
                                <!-- Phần upload file đơn giản hơn -->
                                <div class="mt-1">
                                    <div class="border-2 border-gray-300 border-dashed rounded-md p-4 relative cursor-pointer" id="drop-zone">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            
                                            <div class="mt-2">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Tải lên một file</span>
                                                    <input id="file-upload" name="file" type="file" class="sr-only" required>
                                                </label>
                                                <p class="pl-1 inline">hoặc kéo thả vào đây</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hiển thị tên file -->
                                    <div id="file-name-display" class="hidden mt-3 p-3 bg-green-50 rounded-md border border-green-200">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="font-medium text-green-800">File đã chọn thành công</p>
                                                <p class="text-sm text-green-700 break-all" id="file-name"></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hiển thị file backup (dự phòng) -->
                                    <div class="mt-2 text-sm text-gray-500">
                                        File đã chọn: <span id="file-name-backup">Chưa chọn file</span>
                                    </div>
                                </div>

                                <!-- Thông báo hướng dẫn -->
                                <div class="mt-2 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Hướng dẫn:</strong> Nhấn vào "Tải lên một file" để chọn bài làm của bạn. Sau khi chọn, tên file sẽ hiển thị ngay phía dưới.
                                    </p>
                                </div>
                                
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Dung lượng tối đa: 10MB, hỗ trợ các định dạng phổ biến (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, JPG, PNG)
                                </p>
                            </div>
                        @endif
                        
                        <div class="mt-8 flex justify-center">
                            <a href="{{ route('hoc-vien.bai-tap.show', $baiTap->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Quay lại
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                Nộp bài
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các tham chiếu đến các phần tử DOM cần thiết
        const fileInput = document.getElementById('file-upload');
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileName = document.getElementById('file-name');
        const fileNameBackup = document.getElementById('file-name-backup');
        const form = document.getElementById('submitForm');
        const dropZone = document.getElementById('drop-zone');
        
        console.log('Script được thực thi từ stack scripts');
        console.log('File input:', fileInput);
        console.log('File name display:', fileNameDisplay);
        console.log('File name element:', fileName);
        
        // Giải pháp đơn giản nhất - cập nhật tên file trực tiếp
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (fileInput.files && fileInput.files.length > 0) {
                    // Cập nhật tên file ở phần dự phòng
                    if (fileNameBackup) {
                        fileNameBackup.textContent = fileInput.files[0].name;
                        fileNameBackup.style.color = '#059669';
                        fileNameBackup.style.fontWeight = 'bold';
                    }
                    
                    // Hiển thị trong phần chính
                    if (fileName) {
                        fileName.textContent = fileInput.files[0].name;
                    }
                    
                    if (fileNameDisplay && fileNameDisplay.classList.contains('hidden')) {
                        fileNameDisplay.classList.remove('hidden');
                    }
                }
            });
        }
        
        // Xử lý kéo thả
        if (dropZone && fileInput) {
            // Ngăn chặn các sự kiện mặc định
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                dropZone.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            
            // Xử lý sự kiện thả
            dropZone.addEventListener('drop', function(e) {
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    
                    // Kích hoạt sự kiện change thủ công
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
            
            // Xử lý sự kiện click
            dropZone.addEventListener('click', function(e) {
                if (!e.target.closest('label')) {
                    fileInput.click();
                }
            });
        }
        
        // Xác nhận nộp bài
        if (form) {
            form.addEventListener('submit', function(e) {
                // Kiểm tra file đã được chọn chưa
                if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
                    const textareaContent = document.getElementById('noi_dung');
                    if (!textareaContent || !textareaContent.value.trim()) {
                        e.preventDefault();
                        alert('Vui lòng chọn file hoặc nhập nội dung bài làm trước khi nộp bài.');
                        return false;
                    }
                }
                
                if (!confirm('Bạn chỉ được nộp bài 1 lần duy nhất. Bạn có chắc chắn muốn nộp bài ngay bây giờ?')) {
                    e.preventDefault();
                    return false;
                }
                
                // Đặt một class để biết form đã submit
                form.classList.add('submitting');
                
                // Cache buster - thêm timestamp vào URL
                const currentUrl = form.action;
                const timestamp = new Date().getTime();
                const separator = currentUrl.includes('?') ? '&' : '?';
                form.action = currentUrl + separator + '_t=' + timestamp;
                
                return true;
            });
        }
        
        // Tích hợp trình soạn thảo văn bản nếu là bài tự luận
        if (typeof CKEDITOR !== 'undefined' && document.getElementById('noi_dung')) {
            CKEDITOR.replace('noi_dung', {
                height: 300,
                removeButtons: 'Source,Styles,Format,Font,FontSize,TextColor,BGColor,Maximize,Flash,Smiley,SpecialChar,PageBreak,Iframe,About'
            });
        }
    });
</script>
@endpush

@section('scripts')
@endsection 