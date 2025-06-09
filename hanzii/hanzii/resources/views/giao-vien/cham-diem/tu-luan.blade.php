@extends('layouts.dashboard')

@section('title', 'Chấm điểm bài tập tự luận')
@section('page-heading', 'Chấm điểm bài tập tự luận')

@php
    $active = 'cham_diem';
    $role = 'giao_vien';
@endphp

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Chấm điểm bài tập tự luận</h1>
        <div class="mt-1 flex items-center">
            <a href="{{ route('giao-vien.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('giao-vien.cham-diem.index') }}" class="text-blue-600 hover:text-blue-800">Chấm điểm</a>
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-600">Chấm điểm bài tập tự luận</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Thành công!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Lỗi!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">
                <svg class="h-5 w-5 inline-block mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                Thông tin bài nộp
            </h2>
            <div>
                <a href="{{ route('giao-vien.cham-diem.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="bg-white overflow-hidden border border-gray-200 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 bg-gray-50 w-1/3">Học viên:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $baiNop->hocVien && $baiNop->hocVien->nguoiDung ? $baiNop->hocVien->nguoiDung->ho_ten : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 bg-gray-50">Lớp học:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($baiNop->baiTap && $baiNop->baiTap->baiHoc && isset($baiNop->baiTap->baiHoc->baiHocLops) && $baiNop->baiTap->baiHoc->baiHocLops->isNotEmpty() && isset($baiNop->baiTap->baiHoc->baiHocLops->first()->lopHoc))
                                            {{ $baiNop->baiTap->baiHoc->baiHocLops->first()->lopHoc->ten }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 bg-gray-50">Bài tập:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $baiNop->baiTap ? $baiNop->baiTap->tieu_de : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 bg-gray-50">Ngày nộp:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($baiNop->ngay_nop) ? \Carbon\Carbon::parse($baiNop->ngay_nop)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 bg-gray-50">Trạng thái:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($baiNop->trang_thai == 'da_nop')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Chờ chấm
                                            </span>
                                        @elseif($baiNop->trang_thai == 'dang_cham')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Đang chấm
                                            </span>
                                        @elseif($baiNop->trang_thai == 'da_cham')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã chấm
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $baiNop->trang_thai }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hướng dẫn chấm điểm
                            </h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Xem kỹ nội dung bài làm của học viên
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Đánh giá dựa trên tiêu chí của bài tập
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Điểm số từ 0-10, có thể có thập phân (ví dụ: 8.5)
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Nhận xét cụ thể để học viên hiểu được điểm mạnh, điểm yếu
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Nội dung bài làm
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        @if($baiNop->noi_dung)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Nội dung bài làm của học viên:</h4>
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50 prose prose-sm max-w-none">
                                    {!! nl2br(e($baiNop->noi_dung)) !!}
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Nội dung bài làm của học viên:</h4>
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50 prose prose-sm max-w-none">
                                    <p class="text-gray-500 italic">Không có nội dung</p>
                                </div>
                            </div>
                        @endif

                        @if($baiNop->file_path)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">File đính kèm:</h4>
                                <div class="border border-gray-200 rounded-md overflow-hidden">
                                    <div class="px-4 py-3 flex justify-between items-center bg-gray-50 border-b border-gray-200">
                                        <span class="text-sm text-gray-700">{{ $baiNop->ten_file ?? 'File đính kèm' }}</span>
                                        <div class="flex space-x-2">
                                            <a href="{{ asset('storage/' . $baiNop->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Xem
                                            </a>
                                            <a href="{{ route('giao-vien.cham-diem.download', $baiNop->id) }}" download class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Tải xuống
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('giao-vien.cham-diem.cham', $baiNop->id) }}" method="POST" novalidate>
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="mb-4">
                            <label for="phan_hoi" class="block text-sm font-medium text-gray-700 mb-1">Nhận xét:</label>
                            <textarea id="phan_hoi" name="phan_hoi" rows="5" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md bg-white" required>{{ old('phan_hoi', $baiNop->phan_hoi) }}</textarea>
                            @error('phan_hoi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label for="diem" class="block text-sm font-medium text-gray-700 mb-1">Điểm số (0-10):</label>
                            <input type="number" id="diem" name="diem" min="0" max="10" step="0.1" value="{{ old('diem', $baiNop->diem) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md bg-white" required>
                            @error('diem')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-sm font-medium text-gray-900">Tiêu chí chấm điểm</h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <p class="text-sm text-gray-700 mb-2"><span class="font-medium">Điểm tối đa:</span> {{ isset($baiNop->baiTap) && isset($baiNop->baiTap->diem_toi_da) ? $baiNop->baiTap->diem_toi_da : 10 }}</p>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <svg class="h-4 w-4 mr-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Hoàn thành yêu cầu: 5 điểm
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-4 w-4 mr-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Độ chính xác: 3 điểm
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-4 w-4 mr-2 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Sáng tạo, phát triển ý tưởng: 2 điểm
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('giao-vien.cham-diem.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hủy
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Lưu điểm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tự động cập nhật trạng thái đang chấm
        $.ajax({
            url: "{{ route('giao-vien.cham-diem.cap-nhat-trang-thai', $baiNop->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                trang_thai: "dang_cham"
            },
            success: function(response) {
                console.log("Đã cập nhật trạng thái thành đang chấm");
            }
        });
        
        // Xác nhận trước khi rời trang nếu chưa lưu
        let formChanged = false;
        $('form input, form textarea').on('change', function() {
            formChanged = true;
        });
        
        $(window).on('beforeunload', function() {
            if (formChanged) {
                return "Bạn có thông tin chưa lưu. Bạn có chắc chắn muốn rời khỏi trang này?";
            }
        });
        
        $('form').on('submit', function() {
            formChanged = false;
        });
        
        // Đóng modal khi click bên ngoài
        $(window).on('click', function(event) {
            if ($(event.target).hasClass('fixed') && $(event.target).hasClass('inset-0')) {
                $('#yeu-cau-nop-lai-modal').addClass('hidden');
            }
        });
    });
</script>
@endsection
