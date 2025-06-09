@extends('layouts.dashboard')

@section('title', 'Tìm kiếm lớp học')
@section('page-heading', 'Tìm kiếm lớp học')

@php
    $active = 'lop-hoc';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Tìm kiếm lớp học</h2>
                
                <a href="{{ route('hoc-vien.lop-hoc.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Quay lại danh sách
                </a>
            </div>
            
            <p class="text-gray-600 mb-6">Nhập mã lớp học để tìm kiếm thông tin và gửi yêu cầu tham gia. Mã lớp học thường được cung cấp bởi giáo viên hoặc quản trị viên.</p>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('hoc-vien.lop-hoc.tim-kiem') }}" method="POST" class="max-w-md">
                @csrf
                <div class="mb-6">
                    <label for="ma_lop" class="block text-sm font-medium text-gray-700 mb-2">Mã lớp học <span class="text-red-600">*</span></label>
                    <input type="text" name="ma_lop" id="ma_lop" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="Nhập mã lớp học">
                    <p class="mt-1 text-sm text-gray-500">Ví dụ: HSK1-01, TQ2023-H, ONLINE-2024, v.v.</p>
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tìm kiếm
                </button>
            </form>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Các câu hỏi thường gặp</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-800">Làm thế nào để biết mã lớp học?</h4>
                    <p class="text-gray-600">Mã lớp học thường được cung cấp bởi giáo viên, trợ giảng hoặc được gửi qua email khi bạn đăng ký lớp học.</p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-800">Tôi không tìm thấy lớp học của mình?</h4>
                    <p class="text-gray-600">Đảm bảo bạn nhập đúng mã lớp học, bao gồm cả chữ hoa, chữ thường và dấu gạch ngang. Nếu vẫn không tìm thấy, vui lòng liên hệ với giáo viên hoặc quản trị viên.</p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-800">Quy trình tham gia lớp học như thế nào?</h4>
                    <p class="text-gray-600">Sau khi tìm thấy lớp học, bạn có thể gửi yêu cầu tham gia. Giáo viên sẽ xem xét và phê duyệt yêu cầu của bạn. Bạn sẽ được thông báo khi yêu cầu được chấp nhận.</p>
                </div>
            </div>
        </div>
    </div>
@endsection 