<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\DangKyHoc;
use App\Models\DangKyQuanTam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KhoaHocController extends Controller
{
    /**
     * Hiển thị danh sách khóa học và thống kê
     */
    public function index(Request $request)
    {
        // Xử lý tìm kiếm và lọc dữ liệu
        $query = KhoaHoc::query()->with(['lopHocs']);
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('ten', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
        }
        
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        // Sắp xếp kết quả
        $query->orderBy('tao_luc', 'desc');
        
        $khoaHocs = $query->paginate(9);
        
        // Thống kê
        $tongKhoaHoc = KhoaHoc::count();
        $tongLopDangHoc = LopHoc::where('trang_thai', 'dang_dien_ra')->count();
        $tongHocVienDangHoc = DangKyHoc::whereHas('lopHoc', function($q) {
            $q->where('trang_thai', 'dang_dien_ra');
        })->where('trang_thai', 'da_xac_nhan')->count();
        
        // Tính doanh thu tháng hiện tại
        $thangHienTai = Carbon::now()->startOfMonth();
        $doanhThuThang = DangKyHoc::join('lop_hocs', 'dang_ky_hocs.lop_hoc_id', '=', 'lop_hocs.id')
            ->join('khoa_hocs', 'lop_hocs.khoa_hoc_id', '=', 'khoa_hocs.id')
            ->where('dang_ky_hocs.trang_thai', 'da_xac_nhan')
            ->where('dang_ky_hocs.ngay_dang_ky', '>=', $thangHienTai)
            ->sum('khoa_hocs.hoc_phi');
        
        return view('admin.khoa-hoc.index', compact(
            'khoaHocs', 
            'tongKhoaHoc', 
            'tongLopDangHoc', 
            'tongHocVienDangHoc', 
            'doanhThuThang'
        ));
    }

    /**
     * Hiển thị form tạo khóa học mới
     */
    public function create()
    {
        return view('admin.khoa-hoc.create');
    }

    /**
     * Lưu khóa học mới vào database
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ten' => 'required|string|max:255',
            'mo_ta' => 'required|string',
            'hoc_phi' => 'required|numeric|min:0',
            'thoi_gian_hoan_thanh' => 'nullable|string|max:100',
            'tong_so_bai' => 'nullable|integer|min:0',
            'trang_thai' => 'required|in:hoat_dong,tam_ngung,da_ket_thuc',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);
        
        // Xử lý tải lên hình ảnh
        if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
            $hinhAnhPath = $request->file('hinh_anh')->store('public/khoa-hoc');
        } else {
            $hinhAnhPath = null;
        }
        
        // Tạo khóa học mới
        $khoaHoc = new KhoaHoc;
        $khoaHoc->ten = $validated['ten'];
        $khoaHoc->mo_ta = $validated['mo_ta'];
        $khoaHoc->hoc_phi = $validated['hoc_phi'];
        $khoaHoc->thoi_gian_hoan_thanh = $validated['thoi_gian_hoan_thanh'] ?? null;
        $khoaHoc->tong_so_bai = $validated['tong_so_bai'] ?? 0;
        $khoaHoc->trang_thai = $validated['trang_thai'];
        
        // Luôn gán giá trị cho hinh_anh (chuỗi rỗng nếu không có file)
        $khoaHoc->hinh_anh = $hinhAnhPath ?? '';
        
        $khoaHoc->save();
        
        return redirect()->route('admin.khoa-hoc.show', $khoaHoc->id)
            ->with('success', 'Khóa học đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết khóa học
     */
    public function show($id)
    {
        $khoaHoc = KhoaHoc::with(['lopHocs', 'baiHocs'])->findOrFail($id);
        
        // Lấy danh sách lớp học đang diễn ra
        $lopHocDangDienRa = $khoaHoc->lopHocs()
            ->with(['giaoVien.nguoiDung', 'dangKyHocs'])
            ->where('trang_thai', 'dang_dien_ra')
            ->orderBy('ngay_bat_dau', 'desc')
            ->limit(5)
            ->get();
        
        // Tính tổng số học viên đã đăng ký
        $tongSoHocVien = DangKyHoc::whereIn('lop_hoc_id', $khoaHoc->lopHocs->pluck('id'))
            ->where('trang_thai', 'da_xac_nhan')
            ->count();
        
        return view('admin.khoa-hoc.show', compact('khoaHoc', 'lopHocDangDienRa', 'tongSoHocVien'));
    }

    /**
     * Hiển thị form chỉnh sửa khóa học
     */
    public function edit($id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        
        return view('admin.khoa-hoc.edit', compact('khoaHoc'));
    }

    /**
     * Cập nhật thông tin khóa học
     */
    public function update(Request $request, $id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ten' => 'required|string|max:255',
            'mo_ta' => 'required|string',
            'hoc_phi' => 'required|numeric|min:0',
            'thoi_gian_hoan_thanh' => 'nullable|string|max:100',
            'tong_so_bai' => 'nullable|integer|min:0',
            'trang_thai' => 'required|in:hoat_dong,tam_ngung,da_ket_thuc',
            'hinh_anh' => 'nullable|image|max:2048',
            'xoa_hinh_anh' => 'nullable|boolean',
        ]);
        
        // Xử lý tải lên hình ảnh mới hoặc xóa hình ảnh hiện tại
        if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
            // Xóa hình ảnh cũ nếu có
            if ($khoaHoc->hinh_anh) {
                Storage::delete($khoaHoc->hinh_anh);
            }
            
            // Lưu hình ảnh mới
            $hinhAnhPath = $request->file('hinh_anh')->store('public/khoa-hoc');
            $khoaHoc->hinh_anh = $hinhAnhPath;
        } elseif ($request->has('xoa_hinh_anh') && $request->xoa_hinh_anh) {
            // Xóa hình ảnh nếu có yêu cầu
            if ($khoaHoc->hinh_anh) {
                Storage::delete($khoaHoc->hinh_anh);
            }
            $khoaHoc->hinh_anh = '';
        }
        
        // Cập nhật thông tin khóa học
        $khoaHoc->ten = $validated['ten'];
        $khoaHoc->mo_ta = $validated['mo_ta'];
        $khoaHoc->hoc_phi = $validated['hoc_phi'];
        $khoaHoc->thoi_gian_hoan_thanh = $validated['thoi_gian_hoan_thanh'] ?? null;
        $khoaHoc->tong_so_bai = $validated['tong_so_bai'] ?? 0;
        $khoaHoc->trang_thai = $validated['trang_thai'];
        $khoaHoc->save();
        
        return redirect()->route('admin.khoa-hoc.show', $khoaHoc->id)
            ->with('success', 'Khóa học đã được cập nhật thành công!');
    }

    /**
     * Xóa khóa học
     */
    public function destroy($id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        
        // Kiểm tra nếu khóa học đang có lớp học
        $lopHocCount = $khoaHoc->lopHocs()->count();
        if ($lopHocCount > 0) {
            return redirect()->route('admin.khoa-hoc.index')
                ->with('error', 'Không thể xóa khóa học này vì đang có '.$lopHocCount.' lớp học liên quan.');
        }
        
        // Xóa hình ảnh nếu có
        if ($khoaHoc->hinh_anh) {
            Storage::delete($khoaHoc->hinh_anh);
        }
        
        // Xóa các bài học liên quan
        $khoaHoc->baiHocs()->delete();
        
        // Xóa khóa học
        $khoaHoc->delete();
        
        return redirect()->route('admin.khoa-hoc.index')
            ->with('success', 'Khóa học đã được xóa thành công!');
    }
} 