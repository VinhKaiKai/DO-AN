@props(['binhLuans', 'baiHocId', 'lopHocId', 'role'])

<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            Bình luận ({{ count($binhLuans) }})
        </h3>
    </div>

    <!-- Form thêm bình luận -->
    <div class="px-4 py-4 sm:px-6 border-b border-gray-200 bg-gray-50">
        <form action="{{ route($role.'.binh-luan.store') }}" method="POST" class="flex flex-col space-y-3">
            @csrf
            <input type="hidden" name="bai_hoc_id" value="{{ $baiHocId }}">
            <input type="hidden" name="lop_hoc_id" value="{{ $lopHocId }}">

            <div>
                <label for="noi_dung" class="block text-sm font-medium text-gray-700 mb-1">Thêm bình luận của
                    bạn</label>
                <textarea id="noi_dung" name="noi_dung" rows="3" required
                    class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Viết bình luận của bạn..."></textarea>
            </div>

            <div class="text-right">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Gửi bình luận
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách bình luận -->
    <div class="divide-y divide-gray-200">
        @forelse($binhLuans->whereNull('binh_luan_goc_id') as $binhLuan)
        <div class="px-4 py-4 sm:px-6">
            <div class="flex justify-between items-start">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                            @if($binhLuan->nguoiDung && $binhLuan->nguoiDung->anh_dai_dien)
                            <img src="{{ asset('storage/' . $binhLuan->nguoiDung->anh_dai_dien) }}" alt="Avatar"
                                class="h-10 w-10 rounded-full">
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $binhLuan->nguoiDung ? $binhLuan->nguoiDung->ho_ten : 'Người dùng không tồn tại' }}
                            </p>

                            @if($binhLuan->nguoiDung && $binhLuan->nguoiDung->vaiTro)
                            @if($binhLuan->nguoiDung->vaiTro->ten == 'giao_vien')
                            <span
                                class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Giáo
                                viên</span>
                            @elseif($binhLuan->nguoiDung->vaiTro->ten == 'tro_giang')
                            <span
                                class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">Trợ
                                giảng</span>
                            @endif
                            @endif

                            <span class="ml-2 text-xs text-gray-500">{{ $binhLuan->tao_luc->diffForHumans() }}</span>
                        </div>

                        <!-- Nội dung bình luận -->
                        <div class="mt-1 text-sm text-gray-700">
                            <p>{{ $binhLuan->noi_dung }}</p>
                        </div>

                        <!-- Chức năng dành cho trợ giảng trả lời học viên -->
                        @if($role == 'tro-giang' && $binhLuan->nguoiDung && $binhLuan->nguoiDung->vaiTro &&
                        $binhLuan->nguoiDung->vaiTro->ten == 'hoc_vien')
                        <div class="mt-2 flex items-center space-x-2">
                            <button type="button" class="reply-button text-xs text-blue-600 hover:text-blue-800"
                                data-binh-luan-id="{{ $binhLuan->id }}">
                                Trả lời
                            </button>
                        </div>

                        <!-- Form trả lời -->
                        <div id="reply-form-{{ $binhLuan->id }}" class="mt-2 hidden">
                            <form action="{{ route('tro-giang.binh-luan.phan-hoi') }}" method="POST" class="space-y-2">
                                @csrf
                                <input type="hidden" name="bai_hoc_id" value="{{ $binhLuan->bai_hoc_id }}">
                                <input type="hidden" name="lop_hoc_id" value="{{ $binhLuan->lop_hoc_id }}">
                                <input type="hidden" name="binh_luan_goc_id" value="{{ $binhLuan->id }}">

                                <div>
                                    <textarea name="noi_dung" rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Nhập trả lời của bạn..."></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="button"
                                        class="cancel-reply text-xs mr-2 py-1 px-3 border border-gray-300 rounded-md hover:bg-gray-50"
                                        data-binh-luan-id="{{ $binhLuan->id }}">
                                        Hủy
                                    </button>
                                    <button type="submit"
                                        class="text-xs py-1 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Gửi trả lời
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                @if($binhLuan->nguoi_dung_id == session('nguoi_dung_id') || $role == 'giao-vien')
                <div>
                    <form action="{{ route($role.'.binh-luan.destroy', $binhLuan->id) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Hiển thị phản hồi bình luận nếu có -->
            @if(isset($binhLuan->phanHois) && $binhLuan->phanHois->count() > 0)
            @foreach($binhLuan->phanHois as $phanHoi)
            <div class="mt-3 pl-8 ml-3 border-l-2 border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div
                                class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                @if($phanHoi->nguoiDung && $phanHoi->nguoiDung->anh_dai_dien)
                                <img src="{{ asset('storage/' . $phanHoi->nguoiDung->anh_dai_dien) }}" alt="Avatar"
                                    class="h-8 w-8 rounded-full">
                                @else
                                @if($phanHoi->nguoiDung)
                                <span class="text-xs font-medium">
                                    {{ strtoupper(substr($phanHoi->nguoiDung->ho_ten ?? '', 0, 1)) }}
                                </span>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $phanHoi->nguoiDung ? $phanHoi->nguoiDung->ho_ten : 'Người dùng không tồn tại' }}
                                </p>

                                @if($phanHoi->nguoiDung && $phanHoi->nguoiDung->vaiTro)
                                @if($phanHoi->nguoiDung->vaiTro->ten == 'giao_vien')
                                <span
                                    class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Giáo
                                    viên</span>
                                @elseif($phanHoi->nguoiDung->vaiTro->ten == 'tro_giang')
                                <span
                                    class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">Trợ
                                    giảng</span>
                                @endif
                                @endif

                                <span class="ml-2 text-xs text-gray-500">{{ $phanHoi->tao_luc->diffForHumans() }}</span>
                            </div>
                            <div class="mt-1 text-sm text-gray-700">
                                <p>{{ $phanHoi->noi_dung }}</p>
                            </div>
                        </div>
                    </div>

                    @if($phanHoi->nguoi_dung_id == session('nguoi_dung_id') || $role == 'giao-vien')
                    <div>
                        <form action="{{ route($role.'.binh-luan.destroy', $phanHoi->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa phản hồi này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
        @empty
        <div class="px-4 py-12 text-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bình luận nào</h3>
            <p class="mt-1 text-sm text-gray-500">Hãy là người đầu tiên chia sẻ ý kiến về bài học này.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Script để hiển thị/ẩn form phản hồi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thêm event listener cho các nút trả lời
    const replyButtons = document.querySelectorAll('.reply-button');
    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const binhLuanId = this.getAttribute('data-binh-luan-id');
            const replyForm = document.getElementById('reply-form-' + binhLuanId);
            replyForm.classList.toggle('hidden');
        });
    });

    // Thêm event listener cho các nút hủy
    const cancelButtons = document.querySelectorAll('.cancel-reply');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const binhLuanId = this.getAttribute('data-binh-luan-id');
            const replyForm = document.getElementById('reply-form-' + binhLuanId);
            replyForm.classList.add('hidden');
        });
    });
});
</script>