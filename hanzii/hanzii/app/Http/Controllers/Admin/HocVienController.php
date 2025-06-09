<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\DangKyHoc;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HocVienController extends Controller
{
    /**
     * Hiển thị danh sách học viên
     */
    public function index(Request $request)
    {
        $query = HocVien::with('nguoiDung');

        // Xử lý tìm kiếm
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = '%' . $request->q . '%';
            $query->whereHas('nguoiDung', function($q) use ($searchTerm) {
                $q->where('ho', 'like', $searchTerm)
                  ->orWhere('ten', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('so_dien_thoai', 'like', $searchTerm);
            });
        }

        // Xử lý sắp xếp
        $sortField = $request->sort ?? 'tao_luc';
        $sortDirection = $request->direction ?? 'desc';

        if ($sortField === 'ho_ten') {
            $query->join('nguoi_dungs', 'hoc_viens.nguoi_dung_id', '=', 'nguoi_dungs.id')
                  ->orderBy('nguoi_dungs.ho', $sortDirection)
                  ->orderBy('nguoi_dungs.ten', $sortDirection)
                  ->select('hoc_viens.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $hocViens = $query->paginate(10);

        return view('admin.hoc-vien.index', compact('hocViens'));
    }

    /**
     * Hiển thị form tạo học viên mới
     */
    public function create()
    {
        $lopHocs = LopHoc::where('trang_thai', '!=', 'da_hoan_thanh')->pluck('ten', 'id');
        return view('admin.hoc-vien.create', compact('lopHocs'));
    }

    /**
     * Lưu thông tin học viên mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:nguoi_dungs,email',
            'so_dien_thoai' => 'required|string|unique:nguoi_dungs,so_dien_thoai',
            'ngay_sinh' => 'required|date',
            'dia_chi' => 'nullable|string',
            'trinh_do_hoc_van' => 'nullable|string',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
        ]);

        try {
            DB::beginTransaction();

            // Tạo người dùng mới
            $nguoiDung = new NguoiDung();
            $nguoiDung->ho = $request->ho;
            $nguoiDung->ten = $request->ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
            $nguoiDung->mat_khau = Hash::make('hocvientiengtrunglythu');
            $nguoiDung->loai_tai_khoan = 'hoc_vien';
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();

            // Tạo học viên
            $hocVien = new HocVien();
            $hocVien->nguoi_dung_id = $nguoiDung->id;
            $hocVien->ngay_sinh = $request->ngay_sinh;
            $hocVien->trinh_do_hoc_van = $request->trinh_do_hoc_van;
            $hocVien->trang_thai = 'hoat_dong';
            $hocVien->save();

            // Liên kết với vai trò học viên
            $vaiTroHocVien = DB::table('vai_tros')->where('ten', 'hoc_vien')->first();
            if ($vaiTroHocVien) {
                DB::table('vai_tro_nguoi_dungs')->insert([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'vai_tro_id' => $vaiTroHocVien->id,
                    'tao_luc' => now(),
                    'cap_nhat_luc' => now(),
                ]);
            }

            // Đăng ký vào lớp học nếu có
            if ($request->filled('lop_hoc_id')) {
                $dangKyHoc = new DangKyHoc();
                $dangKyHoc->hoc_vien_id = $hocVien->id;
                $dangKyHoc->lop_hoc_id = $request->lop_hoc_id;
                $dangKyHoc->ngay_dang_ky = now();
                $dangKyHoc->trang_thai = 'da_xac_nhan';
                $dangKyHoc->save();
            }

            DB::commit();

            return redirect()->route('admin.hoc-vien.index')
                ->with('success', 'Thêm học viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hiển thị thông tin chi tiết học viên
     */
    public function show($id)
    {
        $hocVien = HocVien::with(['nguoiDung', 'dangKyHocs.lopHoc.khoaHoc'])->findOrFail($id);
        return view('admin.hoc-vien.show', compact('hocVien'));
    }

    /**
     * Hiển thị form chỉnh sửa học viên
     */
    public function edit($id)
    {
        $hocVien = HocVien::with('nguoiDung')->findOrFail($id);
        return view('admin.hoc-vien.edit', compact('hocVien'));
    }

    /**
     * Cập nhật thông tin học viên
     */
    public function update(Request $request, $id)
    {
        $hocVien = HocVien::findOrFail($id);
        
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:nguoi_dungs,email,' . $hocVien->nguoi_dung_id,
            'so_dien_thoai' => 'required|string|unique:nguoi_dungs,so_dien_thoai,' . $hocVien->nguoi_dung_id,
            'ngay_sinh' => 'required|date',
            'dia_chi' => 'nullable|string',
            'trinh_do_hoc_van' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,khong_hoat_dong',
        ]);

        try {
            DB::beginTransaction();

            // Cập nhật thông tin người dùng
            $nguoiDung = $hocVien->nguoiDung;
            $nguoiDung->ho = $request->ho;
            $nguoiDung->ten = $request->ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();

            // Cập nhật thông tin học viên
            $hocVien->ngay_sinh = $request->ngay_sinh;
            $hocVien->trinh_do_hoc_van = $request->trinh_do_hoc_van;
            $hocVien->trang_thai = $request->trang_thai;
            $hocVien->save();

            DB::commit();

            return redirect()->route('admin.hoc-vien.show', $hocVien->id)
                ->with('success', 'Cập nhật học viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Xóa học viên
     */
    public function destroy($id)
    {
        $hocVien = HocVien::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Kiểm tra nếu học viên có đăng ký hay dữ liệu liên quan
            if ($hocVien->dangKyHocs()->count() > 0) {
                return back()->withErrors(['msg' => 'Không thể xóa học viên này vì đã có dữ liệu đăng ký học.']);
            }
            
            // Xóa thông tin liên kết vai trò
            DB::table('vai_tro_nguoi_dungs')->where('nguoi_dung_id', $hocVien->nguoi_dung_id)->delete();
            
            // Lấy id người dùng trước khi xóa học viên
            $nguoiDungId = $hocVien->nguoi_dung_id;
            
            // Xóa học viên
            $hocVien->delete();
            
            // Xóa người dùng
            NguoiDung::destroy($nguoiDungId);
            
            DB::commit();
            
            return redirect()->route('admin.hoc-vien.index')
                ->with('success', 'Đã xóa học viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
} 