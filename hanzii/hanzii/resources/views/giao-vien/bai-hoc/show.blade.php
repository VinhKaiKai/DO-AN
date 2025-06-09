@extends('layouts.dashboard')

@section('title', 'Chi tiết bài học')
@section('page-heading', 'Chi tiết bài học')

@php
    $active = 'bai-hoc';
    $role = 'giao_vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="javascript:history.back();" class="text-red-600 hover:text-red-800 mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại
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

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-red-100 rounded-full flex items-center justify-center text-red-500">
                            <span class="text-lg font-medium">{{ $baiHoc->thu_tu }}</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $baiHoc->tieu_de }}</h3>
                            <div class="flex space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $baiHoc->thoi_luong }} phút
                                </span>
                                <span class="flex items-center">
                                    @if($baiHoc->loai == 'video')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Video
                                    @elseif($baiHoc->loai == 'van_ban')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Văn bản
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ $baiHoc->loai }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="p-6">
                <!-- Nội dung bài học -->
                @if($baiHoc->loai == 'video' && $baiHoc->url_video)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Video bài học</h3>
                        <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-lg shadow-md max-w-full">
                            @php
                                $videoUrl = $baiHoc->url_video;
                                $youtubeId = '';
                                if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches)) {
                                    $youtubeId = $matches[1];
                                }
                            @endphp
                            
                            @if($youtubeId)
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen 
                                    class="absolute top-0 left-0 w-full h-full"
                                ></iframe>
                            @else
                                <div class="bg-gray-100 p-4 text-center rounded-lg absolute top-0 left-0 w-full h-full flex items-center justify-center">
                                    <div>
                                        <p class="text-gray-500 mb-2">URL video không hợp lệ hoặc không được hỗ trợ.</p>
                                        <p class="text-sm text-gray-400">URL hiện tại: {{ $videoUrl }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nội dung bài học</h3>
                    <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none">
                        {!! $baiHoc->noi_dung !!}
                    </div>
                </div>

                <!-- Danh sách tài liệu bổ trợ -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tài liệu đính kèm</h3>
                        <a href="{{ route('giao-vien.bai-hoc.edit', $baiHoc->id) }}#tai-lieu" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-1.5"></i>
                            Thêm tài liệu
                        </a>
                    </div>

                    @if($baiHoc->taiLieuBoTros->count() > 0)
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach($baiHoc->taiLieuBoTros as $taiLieu)
                                    <li class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $taiLieu->tieu_de }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $taiLieu->created_at ? $taiLieu->created_at->format('d/m/Y H:i') : 'Chưa có thông tin' }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ Storage::url($taiLieu->duong_dan_file) }}" target="_blank" class="inline-flex items-center py-1.5 px-3 rounded-md bg-blue-100 text-blue-700 text-sm hover:bg-blue-200 transition-colors">
                                                    <i class="fas fa-download mr-1"></i> Tải xuống
                                                </a>
                                                <form action="{{ route('giao-vien.tai-lieu.destroy', $taiLieu->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-100 text-sm text-red-600 hover:text-red-800  px-2 py-1 rounded-md" onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?')">
                                                            <i class="fas fa-trash-alt"></i> Xóa
                                                        </button>
                                                    </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-8 text-center">
                                <i class="fas fa-file-alt text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Chưa có tài liệu đính kèm cho bài học này</p>
                                <a href="{{ route('giao-vien.bai-hoc.edit', $baiHoc->id) }}#tai-lieu" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i> Thêm tài liệu
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Danh sách bài tập của bài học -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Danh sách bài tập</h3>
                        <a href="{{ route('giao-vien.bai-tap.create', ['bai_hoc_id' => $baiHoc->id, 'lop_hoc_id' => $lopHoc->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-1.5"></i>
                            Thêm bài tập
                        </a>
                    </div>
                    
                    @if(isset($baiTaps) && $baiTaps->count() > 0)
                        <div class="bg-white shadow overflow-hidden rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tiêu đề
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Loại
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Điểm tối đa
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hạn nộp
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($baiTaps as $baiTap)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $baiTap->tieu_de }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($baiTap->noi_dung), 50) }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $baiTap->loai == 'trac_nghiem' ? 'bg-blue-100 text-blue-800' : 
                                                        ($baiTap->loai == 'tu_luan' ? 'bg-green-100 text-green-800' : 
                                                        'bg-yellow-100 text-yellow-800') }}">
                                                        @if($baiTap->loai == 'tu_luan')
                                                            <i class="fas fa-pen-fancy mr-1"></i> Tự luận
                                                        @elseif($baiTap->loai == 'upload')
                                                            <i class="fas fa-upload mr-1"></i> Upload
                                                        @else
                                                            {{ $baiTap->loai }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $baiTap->diem_toi_da }} điểm
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    @if($baiTap->han_nop)
                                                        {{ \Carbon\Carbon::parse($baiTap->han_nop)->format('d/m/Y H:i') }}
                                                    @else
                                                        <span class="text-gray-400">Không giới hạn</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('giao-vien.bai-tap.show', $baiTap->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </a>
                                                    <a href="{{ route('giao-vien.bai-tap.edit', $baiTap->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                    <form action="{{ route('giao-vien.bai-tap.destroy', $baiTap->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa bài tập này?')">
                                                            <i class="fas fa-trash-alt"></i> Xóa
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-8 text-center">
                                <i class="fas fa-tasks text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Chưa có bài tập nào cho bài học này</p>
                                <a href="{{ route('giao-vien.bai-tap.create', ['bai_hoc_id' => $baiHoc->id, 'lop_hoc_id' => $lopHoc->id]) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i> Thêm bài tập
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
        
            </div>
            
            <div class="flex justify-between px-6 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('giao-vien.bai-hoc.edit', $baiHoc->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Sửa bài học
                    </a>
                    
                    <button type="button" onclick="openDeleteModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Xóa bài học
                    </button>
                </div>
                
              
            </div>
        </div>
    </div>

    <!-- Component bình luận -->
    <x-binh-luan :binhLuans="$baiHoc->binhLuans" :baiHocId="$baiHoc->id" :lopHocId="$lopHoc->id" role="giao-vien" />

    <!-- Modal xóa bài học -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Xác nhận xóa bài học
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Bạn có chắc chắn muốn xóa bài học "{{ $baiHoc->tieu_de }}"? Hành động này không thể hoàn tác.
                                </p>
                                <p class="mt-2 text-sm text-red-500">
                                    Lưu ý: Tất cả dữ liệu liên quan đến bài học này như bài tập, tài liệu, kết quả học tập sẽ bị xóa vĩnh viễn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form method="POST" action="{{ route('giao-vien.bai-hoc.destroy', $baiHoc->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Xác nhận xóa
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Đóng modal khi click bên ngoài
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    });
    
    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush 