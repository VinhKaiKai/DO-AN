@extends('layouts.dashboard')

@section('title', 'Thêm Tài liệu Mới')

@section('page_heading', 'Thêm Tài liệu Mới')

@section('content')
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <form action="{{ route('admin.tai-lieu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="ten_tai_lieu" class="block text-sm font-medium text-gray-700 mb-2">Tên tài liệu <span class="text-red-500">*</span></label>
                    <input type="text" name="ten_tai_lieu" id="ten_tai_lieu" class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('ten_tai_lieu') border-red-500 @enderror" value="{{ old('ten_tai_lieu') }}" required>
                    @error('ten_tai_lieu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="bai_hoc_id" class="block text-sm font-medium text-gray-700 mb-2">Bài học <span class="text-red-500">*</span></label>
                    <select name="bai_hoc_id" id="bai_hoc_id" class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('bai_hoc_id') border-red-500 @enderror" required>
                        <option value="">-- Chọn bài học --</option>
                        @foreach($baiHocs as $baiHoc)
                            <option value="{{ $baiHoc->id }}" {{ old('bai_hoc_id') == $baiHoc->id ? 'selected' : '' }}>
                                {{ $baiHoc->ten_bai_hoc }} ({{ $baiHoc->lopHoc->ten_lop_hoc }})
                            </option>
                        @endforeach
                    </select>
                    @error('bai_hoc_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="mo_ta" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                <textarea name="mo_ta" id="mo_ta" rows="4" class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('mo_ta') border-red-500 @enderror">{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Tệp tài liệu <span class="text-red-500">*</span></label>
                <input type="file" name="file" id="file" class="block w-full mt-1 text-sm @error('file') border-red-500 @enderror" required>
                <p class="text-xs text-gray-500 mt-1">Định dạng được hỗ trợ: PDF, DOCX, XLSX, PPTX, ZIP, RAR (Kích thước tối đa: 10MB)</p>
                @error('file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('admin.tai-lieu.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    <i class="fas fa-save mr-2"></i>Lưu tài liệu
                </button>
            </div>
        </form>
    </div>
@endsection 