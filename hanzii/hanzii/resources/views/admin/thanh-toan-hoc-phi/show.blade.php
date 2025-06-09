@extends('layouts.dashboard')

@section('title', 'Chi tiết thanh toán học phí')
@section('page-heading', 'Chi tiết thanh toán học phí')

@php
    $active = 'thanh-toan-hoc-phi';
    $role = 'admin';
@endphp

@push('styles')
<style>
    /* CSS bổ sung nếu cần */
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Hiển thị thông báo -->
    @if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-medium">Thành công!</strong>
        <span class="block sm:inline ml-1">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-medium">Lỗi!</strong>
        <span class="block sm:inline ml-1">{{ session('error') }}</span>
    </div>
    @endif

    <div class="flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Chi tiết thanh toán học phí - {{ $lopHoc->ten }}</h2>
        <a href="{{ route('admin.thanh-toan-hoc-phi.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin lớp học và thống kê thanh toán -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Thông tin lớp học -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin lớp học</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Tên lớp:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lopHoc->ten }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Mã lớp:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lopHoc->ma_lop }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Khóa học:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lopHoc->khoaHoc->ten }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Học phí:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($lopHoc->khoaHoc->hoc_phi, 0, ',', '.') }} VND</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Số học viên:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hocViens->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Thống kê thanh toán -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Thống kê thanh toán</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Đã thanh toán -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 bg-green-500 flex items-center justify-center rounded-full">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-800">Đã thanh toán</p>
                                <p class="text-lg font-semibold text-green-900">{{ $thanhToanHocPhis->where('trang_thai', 'da_thanh_toan')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chưa thanh toán -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 bg-red-500 flex items-center justify-center rounded-full">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-800">Chưa thanh toán</p>
                                <p class="text-lg font-semibold text-red-900">{{ $hocViens->count() - $thanhToanHocPhis->where('trang_thai', 'da_thanh_toan')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tỷ lệ -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 bg-yellow-500 flex items-center justify-center rounded-full">
                                <i class="fas fa-percentage text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-800">Tỷ lệ</p>
                                <p class="text-lg font-semibold text-yellow-900">
                                    @if($hocViens->count() > 0)
                                        {{ round(($thanhToanHocPhis->where('trang_thai', 'da_thanh_toan')->count() / $hocViens->count()) * 100) }}%
                                    @else
                                        0%
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tổng tiền đã thu -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 bg-blue-500 flex items-center justify-center rounded-full">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-800">Tổng tiền đã thu</p>
                                <p class="text-lg font-semibold text-blue-900">{{ number_format($tongTienDaThu, 0, ',', '.') }} VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách học viên và trạng thái thanh toán -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Danh sách học viên và trạng thái thanh toán</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="table-hoc-vien">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ tên</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái thanh toán</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phương thức</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày thanh toán</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($hocViens as $index => $hocVien)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $hocVien->hoTen }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $hocVien->nguoiDung->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $hocVien->nguoiDung->so_dien_thoai }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $thanhToan = $thanhToanHocPhis[$hocVien->id] ?? null;
                            @endphp
                            
                            @if($thanhToan && $thanhToan->trang_thai == 'da_thanh_toan')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Đã thanh toán</span>
                            @elseif($thanhToan && $thanhToan->trang_thai == 'da_huy')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Đã hủy</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Chưa thanh toán</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($thanhToan)
                                    @if($thanhToan->phuong_thuc_thanh_toan == 'tien_mat')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Tiền mặt</span>
                                    @elseif($thanhToan->phuong_thuc_thanh_toan == 'chuyen_khoan')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Chuyển khoản</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($thanhToan && $thanhToan->ngay_thanh_toan)
                                    {{ \Carbon\Carbon::parse($thanhToan->ngay_thanh_toan)->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                @if($thanhToan)
                                    {{ number_format($thanhToan->so_tien, 0, ',', '.') }} VND
                                @else
                                    {{ number_format($lopHoc->khoaHoc->hoc_phi, 0, ',', '.') }} VND
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($thanhToan)
                                <a href="#" onclick="openDetailModal('{{ $thanhToan->id }}')" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.thanh-toan-hoc-phi.edit', $thanhToan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($thanhToan && $thanhToan->trang_thai != 'da_thanh_toan')
                                    <form action="{{ route('admin.thanh-toan-hoc-phi.update-status', $thanhToan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="da_thanh_toan">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2" onclick="return confirm('Xác nhận đã thanh toán?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($thanhToan && $thanhToan->trang_thai != 'da_huy')
                                    <form action="{{ route('admin.thanh-toan-hoc-phi.cancel-status', $thanhToan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Xác nhận hủy thanh toán?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                                    onclick="openModal('{{ $hocVien->id }}', '{{ $hocVien->hoTen }}', '{{ $lopHoc->id }}', '{{ $lopHoc->khoaHoc->hoc_phi }}')">
                                    <i class="fas fa-plus mr-1"></i> Tạo
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tạo thanh toán -->
<div id="modalThanhToan" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modalThanhToanLabel" aria-modal="true" role="dialog">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.thanh-toan-hoc-phi.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalThanhToanLabel">Tạo thanh toán học phí</h3>
                    <div class="mt-4">
                        <input type="hidden" name="hoc_vien_id" id="hoc_vien_id">
                        <input type="hidden" name="lop_hoc_id" id="lop_hoc_id" value="{{ $lopHoc->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Học viên</label>
                            <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" id="hoc_vien_name" readonly>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền</label>
                            <input type="number" name="so_tien" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" id="so_tien" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                            <select name="phuong_thuc_thanh_toan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                <option value="tien_mat">Tiền mặt</option>
                                <option value="chuyen_khoan">Chuyển khoản</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                            <select name="trang_thai" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                <option value="chua_thanh_toan">Chưa thanh toán</option>
                                <option value="da_thanh_toan">Đã thanh toán</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày thanh toán</label>
                            <input type="date" name="ngay_thanh_toan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="ghi_chu" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Lưu</button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xem chi tiết thanh toán -->
<div id="modalChiTiet" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modalChiTietLabel" aria-modal="true" role="dialog">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalChiTietLabel">Chi tiết thanh toán học phí</h3>
                <div id="chiTietContent" class="mt-4 space-y-4">
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Học viên:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_hoc_vien"></p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Lớp học:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_lop_hoc"></p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Số tiền:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_so_tien"></p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Phương thức thanh toán:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_phuong_thuc"></p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Trạng thái:</p>
                        <p class="text-base font-medium" id="detail_trang_thai"></p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm font-medium text-gray-500">Ngày thanh toán:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_ngay_thanh_toan"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Ghi chú:</p>
                        <p class="text-base font-medium text-gray-900" id="detail_ghi_chu"></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeDetailModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal functions
    function openModal(hocVienId, hocVienName, lopHocId, hocPhi) {
        document.getElementById('hoc_vien_id').value = hocVienId;
        document.getElementById('hoc_vien_name').value = hocVienName;
        document.getElementById('lop_hoc_id').value = lopHocId;
        document.getElementById('so_tien').value = hocPhi;
        document.getElementById('modalThanhToan').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modalThanhToan').classList.add('hidden');
    }
    
    // Chi tiết thanh toán
    function openDetailModal(id) {
        // Lấy thông tin chi tiết từ server bằng AJAX
        fetch(`/admin/thanh-toan-hoc-phi/${id}/get-details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detail_hoc_vien').textContent = data.hoc_vien_name;
                document.getElementById('detail_lop_hoc').textContent = data.lop_hoc_name;
                document.getElementById('detail_so_tien').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.so_tien);
                
                let phuongThuc = data.phuong_thuc_thanh_toan === 'tien_mat' ? 'Tiền mặt' : 'Chuyển khoản';
                document.getElementById('detail_phuong_thuc').textContent = phuongThuc;
                
                let trangThai = 'Chưa thanh toán';
                let trangThaiClass = 'text-red-600';
                if (data.trang_thai === 'da_thanh_toan') {
                    trangThai = 'Đã thanh toán';
                    trangThaiClass = 'text-green-600';
                } else if (data.trang_thai === 'da_huy') {
                    trangThai = 'Đã hủy';
                    trangThaiClass = 'text-gray-600';
                }
                
                document.getElementById('detail_trang_thai').textContent = trangThai;
                document.getElementById('detail_trang_thai').className = `text-base font-medium ${trangThaiClass}`;
                
                document.getElementById('detail_ngay_thanh_toan').textContent = data.ngay_thanh_toan || '-';
                document.getElementById('detail_ghi_chu').textContent = data.ghi_chu || '-';
                
                document.getElementById('modalChiTiet').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải thông tin chi tiết');
            });
    }
    
    function closeDetailModal() {
        document.getElementById('modalChiTiet').classList.add('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        if (typeof($.fn.dataTable) !== 'undefined') {
            $('#table-hoc-vien').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
                }
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('modalThanhToan');
            if (event.target === modal) {
                closeModal();
            }
            
            const modalChiTiet = document.getElementById('modalChiTiet');
            if (event.target === modalChiTiet) {
                closeDetailModal();
            }
        });
    });
</script>
@endpush 