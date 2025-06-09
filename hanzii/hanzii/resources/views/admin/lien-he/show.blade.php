@extends('layouts.dashboard')

@section('title', 'Chi tiết liên hệ')
@section('page-heading', 'Chi tiết liên hệ')

@php
$active = 'lien-he';
$role = 'admin';
@endphp

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.lien-he.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách liên hệ
            </a>
        </div>

        <!-- Thông báo thành công -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Thành công!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Thông tin liên hệ -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Chi tiết liên hệ của người dùng: {{ $lienHe->ho_ten }}
                </h3>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Họ tên -->
                    <div class="bg-gray-50 rounded-lg p-4 flex items-start">
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Họ tên</h4>
                            <div class="mt-1 text-sm text-gray-900 font-semibold">{{ $lienHe->ho_ten }}</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="bg-gray-50 rounded-lg p-4 flex items-start">
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <div class="mt-1 text-sm text-gray-900 font-semibold">{{ $lienHe->email }}</div>
                        </div>
                    </div>

                    <!-- Chủ đề -->
                    <div class="bg-gray-50 rounded-lg p-4 flex items-start">
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center text-white">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Chủ đề</h4>
                            <div class="mt-1 text-sm text-gray-900 font-semibold">{{ $lienHe->chu_de }}</div>
                        </div>
                    </div>

                    <!-- Ngày gửi -->
                    <div class="bg-gray-50 rounded-lg p-4 flex items-start">
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Ngày gửi</h4>
                            <div class="mt-1 text-sm text-gray-900 font-semibold">
                                {{ $lienHe->tao_luc->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung tin nhắn -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Nội dung tin nhắn</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">{!! $lienHe->noi_dung !!}</p>
                </div>
            </div>
        </div>



        <!-- Form phản hồi email -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg">
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Gửi phản hồi qua email</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.lien-he.send-response', $lienHe->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề email</label>
                        <input type="text" name="subject" id="subject"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            value="Phản hồi: {{ $lienHe->chu_de }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Nội dung phản
                            hồi</label>
                        <textarea name="message" id="message" rows="6"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            required>Kính gửi {{ $lienHe->ho_ten }},

Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi xin phản hồi về nội dung bạn đã gửi như sau:

[Nội dung phản hồi của bạn tại đây]

Trân trọng,
Trung tâm Tiếng Trung Hanzii</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-paper-plane mr-2"></i> Gửi phản hồi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection