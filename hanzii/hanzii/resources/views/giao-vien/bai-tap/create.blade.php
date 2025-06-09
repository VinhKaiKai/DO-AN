@extends('layouts.dashboard')

@section('title', 'Tạo bài tập mới')
@section('page-heading', 'Tạo bài tập mới')

@php
    $active = 'bai-tap';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('giao-vien.bai-tap.index', ['bai_hoc_id' => $baiHoc->id]) }}" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách bài tập
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Lỗi!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-plus-circle mr-2"></i> Tạo bài tập mới
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Thông tin bài tập cho bài học "{{ $baiHoc->tieu_de }}"
                </p>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Lớp học: {{ $lopHoc->ten }} ({{ $lopHoc->ma_lop }})
                </p>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('giao-vien.bai-tap.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="bai_hoc_id" value="{{ $baiHoc->id }}">
                
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="tieu_de" class="block text-sm font-medium text-gray-700">Tiêu đề bài tập <span class="text-red-600">*</span></label>
                            <input type="text" name="tieu_de" id="tieu_de" value="{{ old('tieu_de') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('tieu_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loai" class="block text-sm font-medium text-gray-700">Loại bài tập <span class="text-red-600">*</span></label>
                            <select name="loai" id="loai" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="tu_luan" {{ old('loai') == 'tu_luan' ? 'selected' : '' }}>Tự luận</option>
                                <option value="file" {{ old('loai') == 'file' ? 'selected' : '' }}>File</option>
                            </select>
                            @error('loai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="diem_toi_da" class="block text-sm font-medium text-gray-700">Điểm tối đa <span class="text-red-600">*</span></label>
                            <input type="number" name="diem_toi_da" id="diem_toi_da" value="{{ old('diem_toi_da', 10) }}" min="1" max="100" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('diem_toi_da')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="han_nop" class="block text-sm font-medium text-gray-700">Hạn nộp <span class="text-red-600">*</span></label>
                            <input type="datetime-local" name="han_nop" id="han_nop" value="{{ old('han_nop') }}" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('han_nop')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">File đính kèm (tối đa 10MB)</label>
                            <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                       
                        </div>

                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-save mr-2"></i> Lưu bài tập
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
   
    // Biến đếm số câu hỏi
    let cauHoiCount = 1;
    
    // Hàm thêm câu hỏi mới
    function addCauHoi() {
        const container = document.getElementById('cau-hoi-container');
        const cauHoiHtml = `
            <div class="cau-hoi-item border p-4 rounded-md mb-4">
                <div class="flex justify-between mb-2">
                    <h5 class="font-medium">Câu hỏi ${cauHoiCount + 1}</h5>
                    <button type="button" class="text-red-600 hover:text-red-800 delete-cau-hoi" onclick="removeCauHoi(this)">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung câu hỏi <span class="text-red-600">*</span></label>
                    <input type="text" name="cau_hoi[${cauHoiCount}][noi_dung]" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Điểm cho câu hỏi</label>
                    <input type="number" name="cau_hoi[${cauHoiCount}][diem]" value="1" min="0.5" step="0.5" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                </div>
                
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Các đáp án <span class="text-red-600">*</span></label>
                    
                    <div class="dap-an-container">
                        <!-- Đáp án A -->
                        <div class="flex items-center mb-2 dap-an-item">
                            <input type="radio" name="cau_hoi[${cauHoiCount}][dap_an_dung]" value="0" id="dap_an_${cauHoiCount}_0" class="mr-2" checked>
                            <input type="text" name="cau_hoi[${cauHoiCount}][dap_an][0][noi_dung]" placeholder="Đáp án A" class="flex-1 rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500 mr-2">
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeDapAn(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Đáp án B -->
                        <div class="flex items-center mb-2 dap-an-item">
                            <input type="radio" name="cau_hoi[${cauHoiCount}][dap_an_dung]" value="1" id="dap_an_${cauHoiCount}_1" class="mr-2">
                            <input type="text" name="cau_hoi[${cauHoiCount}][dap_an][1][noi_dung]" placeholder="Đáp án B" class="flex-1 rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500 mr-2">
                            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeDapAn(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="addDapAn(this)">
                        <i class="fas fa-plus mr-1"></i> Thêm đáp án
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', cauHoiHtml);
        cauHoiCount++;
    }
    
    // Hàm xóa câu hỏi
    function removeCauHoi(button) {
        const cauHoiItem = button.closest('.cau-hoi-item');
        cauHoiItem.remove();
        
        // Cập nhật lại số thứ tự của các câu hỏi
        document.querySelectorAll('.cau-hoi-item').forEach((item, index) => {
            item.querySelector('h5').textContent = `Câu hỏi ${index + 1}`;
        });
    }
    
    // Biến đếm số đáp án cho mỗi câu hỏi
    const dapAnCounts = {0: 2};
    
    // Hàm thêm đáp án mới
    function addDapAn(button) {
        const dapAnContainer = button.previousElementSibling;
        const cauHoiItem = button.closest('.cau-hoi-item');
        const cauHoiIndex = Array.from(document.querySelectorAll('.cau-hoi-item')).indexOf(cauHoiItem);
        
        if (typeof dapAnCounts[cauHoiIndex] === 'undefined') {
            dapAnCounts[cauHoiIndex] = 2;
        }
        
        const dapAnCount = dapAnCounts[cauHoiIndex];
        const dapAnHtml = `
            <div class="flex items-center mb-2 dap-an-item">
                <input type="radio" name="cau_hoi[${cauHoiIndex}][dap_an_dung]" value="${dapAnCount}" id="dap_an_${cauHoiIndex}_${dapAnCount}" class="mr-2">
                <input type="text" name="cau_hoi[${cauHoiIndex}][dap_an][${dapAnCount}][noi_dung]" placeholder="Đáp án ${String.fromCharCode(65 + dapAnCount)}" class="flex-1 rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500 mr-2">
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeDapAn(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        dapAnContainer.insertAdjacentHTML('beforeend', dapAnHtml);
        dapAnCounts[cauHoiIndex]++;
    }
    
    // Hàm xóa đáp án
    function removeDapAn(button) {
        const dapAnItem = button.closest('.dap-an-item');
        const dapAnContainer = dapAnItem.parentElement;
        
        // Chỉ xóa nếu còn ít nhất 2 đáp án
        if (dapAnContainer.querySelectorAll('.dap-an-item').length > 2) {
            dapAnItem.remove();
        } else {
            alert('Mỗi câu hỏi phải có ít nhất 2 đáp án');
        }
    }
</script>
@endpush 