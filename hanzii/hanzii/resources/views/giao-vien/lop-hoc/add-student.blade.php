@extends('layouts.dashboard')

@section('title', 'Thêm học viên vào lớp')
@section('page-heading', 'Thêm học viên vào lớp')

@php
    $active = 'lop-hoc';
    $role = 'giao-vien';
@endphp

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Thêm học viên vào lớp {{ $lopHoc->ten }}</h2>
            <a href="{{ route('giao-vien.lop-hoc.show', $lopHoc->id) }}" class="mt-4 md:mt-0 inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Thêm học viên bằng mã học viên -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Thêm học viên bằng mã học viên</h3>

            <form action="{{ route('giao-vien.lop-hoc.add-student', $lopHoc->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="ma_hoc_vien" class="block text-sm font-medium text-gray-700 mb-1">Mã học viên</label>
                    <input type="text" name="ma_hoc_vien" id="ma_hoc_vien" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Nhập mã học viên">
                    <small class="text-gray-500">Mã học viên có dạng: HV12345</small>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 disabled:opacity-25 transition">
                        <i class="fas fa-user-plus mr-2"></i> Thêm học viên
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Chia sẻ mã lớp học</h3>
        
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-gray-600 mb-2">Học viên có thể tự tham gia lớp bằng mã lớp học sau:</p>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-red-600" id="class-code">{{ $lopHoc->ma_lop }}</span>
                        <button onclick="copyClassCode()" class="ml-2 px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Học viên có thể nhập mã này tại mục "Tìm lớp học" trên trang chủ</p>
                </div>
                
            </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Lưu ý:</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Chỉ chia sẻ mã lớp học với các học viên cần tham gia. Mã lớp học có thể được sử dụng nhiều lần.</p>
                        <p class="mt-1">Học viên cần có tài khoản trên hệ thống để có thể tham gia lớp học.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách học viên đã tham gia ({{ $lopHoc->hocViens->count() }})</h3>
            <p class="text-sm text-gray-500 mt-1">Danh sách các học viên hiện có trong lớp học</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã học viên
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Điện thoại
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày tham gia
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lopHoc->hocViens as $hocVien)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        {{ strtoupper(substr($hocVien->ho_ten, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $hocVien->ho_ten }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->ma_hoc_vien }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $hocVien->dien_thoai }}
                            </td>
                           
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('giao-vien.hoc-vien.show', $hocVien->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('giao-vien.lop-hoc.remove-student', ['id' => $lopHoc->id, 'hocVienId' => $hocVien->id]) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa học viên này khỏi lớp?')" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Chưa có học viên nào trong lớp học này
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function copyClassCode() {
            const classCode = document.getElementById('class-code');
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = classCode.textContent;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            
            // Thông báo đã copy
            alert('Đã sao chép mã lớp: ' + classCode.textContent);
        }
        
        function shareViaEmail() {
            const classCode = document.getElementById('class-code').textContent;
            const subject = 'Mã tham gia lớp học tại Hanzii - {{ $lopHoc->ten }}';
            const body = 'Xin chào,\n\nBạn đã được mời tham gia lớp học "{{ $lopHoc->ten }}" tại Hanzii.\n\nVui lòng sử dụng mã lớp sau để tham gia: ' + classCode + '\n\nCách tham gia:\n1. Đăng nhập vào tài khoản Hanzii của bạn\n2. Nhấn vào nút "Tìm lớp học" trên trang chủ\n3. Nhập mã lớp và nhấn "Tìm kiếm"\n\nTrân trọng,\nGiáo viên {{ $lopHoc->giaoVien->nguoiDung->ho . ' ' . $lopHoc->giaoVien->nguoiDung->ten }}';
            
            window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        }
    </script>
@endsection 