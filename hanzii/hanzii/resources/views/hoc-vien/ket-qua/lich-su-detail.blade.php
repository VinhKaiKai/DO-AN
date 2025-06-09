@extends('layouts.dashboard')

@section('title', 'Chi tiết kết quả bài kiểm tra')
@section('page-heading', 'Chi tiết kết quả bài kiểm tra')

@php
    $active = 'ket-qua';
    $role = 'hoc_vien';
@endphp

@section('content')
    <div class="mb-6">
        <a href="{{ route('hoc-vien.ket-qua.index') }}" class="inline-flex items-center text-sm text-red-600 hover:text-red-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Quay lại kết quả học tập
        </a>
    </div>

    <div class="mb-6">
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Thông tin bài kiểm tra</h3>
                    <div class="space-y-2">
                        <div class="flex flex-col">
                            <span class="text-gray-600 text-sm">Tên bài kiểm tra:</span>
                            <span class="font-medium">{{ $ketQua->baiKiemTra->ten }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-600 text-sm">Thuộc bài học:</span>
                            <span class="font-medium">{{ $ketQua->baiKiemTra->baiHoc->ten }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-600 text-sm">Lớp học:</span>
                            <span class="font-medium">{{ $ketQua->lopHoc->ten }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-600 text-sm">Thời gian làm bài:</span>
                            <span class="font-medium">{{ $ketQua->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kết quả</h3>
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center border-4 
                            @if($ketQua->diem >= 8) 
                                border-green-500 text-green-600
                            @elseif($ketQua->diem >= 6.5) 
                                border-blue-500 text-blue-600
                            @elseif($ketQua->diem >= 5) 
                                border-yellow-500 text-yellow-600
                            @else 
                                border-red-500 text-red-600
                            @endif">
                            <div class="text-center">
                                <div class="text-3xl font-bold">{{ $ketQua->diem }}</div>
                                <div class="text-sm">Điểm</div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-green-100 text-green-800 p-2 rounded-md">
                            <div class="text-xl font-bold">{{ $ketQua->so_cau_dung }}</div>
                            <div class="text-sm">Câu đúng</div>
                        </div>
                        <div class="bg-red-100 text-red-800 p-2 rounded-md">
                            <div class="text-xl font-bold">{{ $ketQua->so_cau_sai }}</div>
                            <div class="text-sm">Câu sai</div>
                        </div>
                        <div class="bg-gray-100 text-gray-800 p-2 rounded-md">
                            <div class="text-xl font-bold">{{ $ketQua->so_cau_chua_lam }}</div>
                            <div class="text-sm">Chưa làm</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <div>
        <x-card title="Chi tiết các câu hỏi">
            <div class="space-y-6">
                @foreach($chiTietKetQuas as $index => $chiTiet)
                    <div class="p-4 border rounded-lg 
                        @if(!$chiTiet->da_tra_loi)
                            bg-gray-50
                        @elseif($chiTiet->dap_an_id == $chiTiet->dap_an_dung_id)
                            bg-green-50
                        @else
                            bg-red-50
                        @endif">
                        <div class="flex items-start gap-2">
                            <div class="bg-gray-200 rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                                <span class="text-sm font-medium">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium mb-2">{{ $chiTiet->cauHoi->noi_dung }}</div>
                                <div class="ml-2 space-y-2">
                                    @foreach($chiTiet->cauHoi->dapAns as $dapAn)
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 rounded-full border flex items-center justify-center mr-2
                                                @if($dapAn->id == $chiTiet->dap_an_id && $chiTiet->dap_an_id == $chiTiet->dap_an_dung_id)
                                                    bg-green-500 text-white border-green-500
                                                @elseif($dapAn->id == $chiTiet->dap_an_id)
                                                    bg-red-500 text-white border-red-500
                                                @elseif($dapAn->id == $chiTiet->dap_an_dung_id)
                                                    border-green-500
                                                @else
                                                    border-gray-300
                                                @endif">
                                                @if($dapAn->id == $chiTiet->dap_an_id)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="
                                                @if($dapAn->id == $chiTiet->dap_an_dung_id)
                                                    font-medium text-green-700
                                                @elseif($dapAn->id == $chiTiet->dap_an_id)
                                                    font-medium text-red-700
                                                @endif">
                                                {{ $dapAn->noi_dung }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($chiTiet->cauHoi->giai_thich)
                                    <div class="mt-3 p-3 bg-blue-50 rounded-md">
                                        <div class="text-sm font-medium text-blue-700 mb-1">Giải thích:</div>
                                        <div class="text-sm text-blue-800">{{ $chiTiet->cauHoi->giai_thich }}</div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-2 flex-shrink-0">
                                @if(!$chiTiet->da_tra_loi)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Không làm</span>
                                @elseif($chiTiet->dap_an_id == $chiTiet->dap_an_dung_id)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Đúng</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Sai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
@endsection