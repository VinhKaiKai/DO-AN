<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\NguoiDung;
use App\Models\GiaoVien;
use App\Models\TroGiang;
use App\Models\VaiTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NguoiDungController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $vaiTroId = $request->input('vai_tro_id');
        
        $query = NguoiDung::with('vaiTro');
        
        // Tìm kiếm nếu có
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ho', 'like', "%{$search}%")
                  ->orWhere('ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }
        

        
        // Lọc theo vai trò
        if ($vaiTroId) {
            $query->where('vai_tro_id', $vaiTroId);
        }
        
        $nguoiDungs = $query->paginate(10);
        $vaiTros = VaiTro::pluck('ten', 'id');
        
        return view('admin.nguoi-dung.index', compact('nguoiDungs', 'vaiTros',  'vaiTroId', 'search'));
    }
    
    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        $vaiTros = VaiTro::all();
        return view('admin.nguoi-dung.create', compact('vaiTros'));
    }
    
    /**
     * Lưu người dùng mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:nguoi_dungs,email',
            'so_dien_thoai' => 'required|string|max:20|unique:nguoi_dungs,so_dien_thoai',
            'password' => 'required|string|min:6|confirmed',
            'dia_chi' => 'nullable|string',
            'vai_tro_ids' => 'required|array',
            'vai_tro_ids.*' => 'exists:vai_tros,id',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Lấy thông tin vai trò đã chọn
            $vaiTroIds = $request->vai_tro_ids;
            $vaiTroId = $vaiTroIds[0] ?? null;
            $loaiTaiKhoan = null;
            
            // Xác định loại tài khoản dựa trên vai trò
            if ($vaiTroId) {
                $vaiTro = VaiTro::find($vaiTroId);
                if ($vaiTro) {
                    switch ($vaiTro->ten) {
                        case 'admin':
                            $loaiTaiKhoan = 'admin';
                            break;
                        case 'giao_vien':
                            $loaiTaiKhoan = 'giao_vien';
                            break;
                        case 'tro_giang':
                            $loaiTaiKhoan = 'tro_giang';
                            break;
                        case 'hoc_vien':
                            $loaiTaiKhoan = 'hoc_vien';
                            break;
                        default:
                            $loaiTaiKhoan = null;
                    }
                }
            }
            
            // Tạo người dùng mới
            $nguoiDung = NguoiDung::create([
                'ho' => $request->ho,
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'mat_khau' => Hash::make($request->password),
                'dia_chi' => $request->dia_chi,
                'loai_tai_khoan' => $loaiTaiKhoan,
                'vai_tro_id' => $vaiTroId,
            ]);
            
            // Tạo profile theo vai trò
            if ($loaiTaiKhoan == 'hoc_vien' || in_array(4, $vaiTroIds)) {
                HocVien::create([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'ngay_sinh' => $request->ngay_sinh,
                    'trinh_do_hoc_van' => $request->trinh_do_hoc_van,
                    'trang_thai' => 'hoat_dong',
                ]);
            } elseif ($loaiTaiKhoan == 'giao_vien' || in_array(2, $vaiTroIds)) {
                GiaoVien::create([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'bang_cap' => $request->bang_cap,
                    'chuyen_mon' => $request->chuyen_mon,
                    'so_nam_kinh_nghiem' => $request->so_nam_kinh_nghiem,
                ]);
            } elseif ($loaiTaiKhoan == 'tro_giang' || in_array(3, $vaiTroIds)) {
                TroGiang::create([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'bang_cap' => $request->bang_cap,
                    'chuyen_mon' => $request->chuyen_mon,
                    'so_nam_kinh_nghiem' => $request->so_nam_kinh_nghiem,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.nguoi-dung.index')
                    ->with('success', 'Tạo người dùng mới thành công!');
                    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Hiển thị chi tiết người dùng
     */
    public function show($id)
    {
        $nguoiDung = NguoiDung::with('vaiTro')->findOrFail($id);
        
        // Lấy thông tin profile tương ứng với vai trò
        if ($nguoiDung->loai_tai_khoan == 'hoc_vien') {
            $hocVien = $nguoiDung->hocVien;
            return view('admin.nguoi-dung.show', compact('nguoiDung', 'hocVien'));
        } elseif ($nguoiDung->vaiTro && $nguoiDung->vaiTro->ten == 'giao_vien') {
            $giaoVien = $nguoiDung->giaoVien;
            return view('admin.nguoi-dung.show', compact('nguoiDung', 'giaoVien'));
        } elseif ($nguoiDung->vaiTro && $nguoiDung->vaiTro->ten == 'tro_giang') {
            $troGiang = $nguoiDung->troGiang;
            return view('admin.nguoi-dung.show', compact('nguoiDung', 'troGiang'));
        }
        
        return view('admin.nguoi-dung.show', compact('nguoiDung'));
    }
    
    /**
     * Hiển thị form sửa người dùng
     */
    public function edit($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $vaiTros = VaiTro::all();
        $nguoiDungVaiTroId = $nguoiDung->vai_tro_id;
        
        // Lấy thông tin profile tương ứng với vai trò
        if ($nguoiDung->loai_tai_khoan == 'hoc_vien') {
            $hocVien = $nguoiDung->hocVien;
            return view('admin.nguoi-dung.edit', compact('nguoiDung', 'vaiTros', 'nguoiDungVaiTroId', 'hocVien'));
        } elseif ($nguoiDung->vaiTro && $nguoiDung->vaiTro->ten == 'giao_vien') {
            $giaoVien = $nguoiDung->giaoVien;
            return view('admin.nguoi-dung.edit', compact('nguoiDung', 'vaiTros', 'nguoiDungVaiTroId', 'giaoVien'));
        } elseif ($nguoiDung->vaiTro && $nguoiDung->vaiTro->ten == 'tro_giang') {
            $troGiang = $nguoiDung->troGiang;
            return view('admin.nguoi-dung.edit', compact('nguoiDung', 'vaiTros', 'nguoiDungVaiTroId', 'troGiang'));
        }
        
        return view('admin.nguoi-dung.edit', compact('nguoiDung', 'vaiTros', 'nguoiDungVaiTroId'));
    }
    
    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, $id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:nguoi_dungs,email,'.$id,
            'so_dien_thoai' => 'required|string|max:20|unique:nguoi_dungs,so_dien_thoai,'.$id,
            'password' => 'nullable|string|min:6|confirmed',
            'dia_chi' => 'nullable|string',
            'vai_tro_ids' => 'required|array',
            'vai_tro_ids.*' => 'exists:vai_tros,id',
        ]);
        
        if ($nguoiDung->loai_tai_khoan == 'hoc_vien') {
            $validator = Validator::make($request->all(), [
                'ngay_sinh' => 'nullable|date',
                'trinh_do_hoc_van' => 'nullable|string|max:255',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'bang_cap' => 'nullable|string',
                'chuyen_mon' => 'nullable|string|max:255',
                'so_nam_kinh_nghiem' => 'nullable|integer|min:0',
            ]);
        }
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Lấy thông tin vai trò đã chọn
            $vaiTroId = $request->vai_tro_ids[0] ?? null;
            $loaiTaiKhoan = null;
            
            // Xác định loại tài khoản dựa trên vai trò
            if ($vaiTroId) {
                $vaiTro = VaiTro::find($vaiTroId);
                if ($vaiTro) {
                    switch ($vaiTro->ten) {
                        case 'admin':
                            $loaiTaiKhoan = 'admin';
                            break;
                        case 'giao_vien':
                            $loaiTaiKhoan = 'giao_vien';
                            break;
                        case 'tro_giang':
                            $loaiTaiKhoan = 'tro_giang';
                            break;
                        case 'hoc_vien':
                            $loaiTaiKhoan = 'hoc_vien';
                            break;
                        default:
                            $loaiTaiKhoan = $nguoiDung->loai_tai_khoan;
                    }
                }
            }
            
            // Cập nhật thông tin cơ bản
            $nguoiDung->update([
                'ho' => $request->ho,
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
                'loai_tai_khoan' => $loaiTaiKhoan,
                'vai_tro_id' => $vaiTroId,
            ]);
            
            // Cập nhật mật khẩu nếu có
            if ($request->filled('password')) {
                $nguoiDung->mat_khau = Hash::make($request->password);
                $nguoiDung->save();
            }
            
            // Cập nhật thông tin profile tương ứng với vai trò
            $vaiTroTen = $nguoiDung->vaiTro ? $nguoiDung->vaiTro->ten : null;
            
            if ($vaiTroTen == 'giao_vien') {
                $giaoVien = GiaoVien::where('nguoi_dung_id', $id)->first();
                if (!$giaoVien) {
                    // Tạo mới nếu chưa có
                    $giaoVien = new GiaoVien();
                    $giaoVien->nguoi_dung_id = $id;
                }
                $giaoVien->bang_cap = $request->bang_cap;
                $giaoVien->chuyen_mon = $request->chuyen_mon;
                $giaoVien->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
                $giaoVien->save();
            } elseif ($vaiTroTen == 'tro_giang') {
                $troGiang = TroGiang::where('nguoi_dung_id', $id)->first();
                if (!$troGiang) {
                    // Tạo mới nếu chưa có
                    $troGiang = new TroGiang();
                    $troGiang->nguoi_dung_id = $id;
                }
                $troGiang->bang_cap = $request->bang_cap;
                $troGiang->chuyen_mon = $request->chuyen_mon;
                $troGiang->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
                $troGiang->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.nguoi-dung.show', $nguoiDung->id)
                    ->with('success', 'Cập nhật thông tin người dùng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Xóa người dùng
     */
    public function destroy($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        
        // Xóa profile tương ứng với vai trò
        $vaiTroTen = $nguoiDung->vaiTro ? $nguoiDung->vaiTro->ten : null;
        
        if ($vaiTroTen == 'giao_vien') {
            $giaoVien = GiaoVien::where('nguoi_dung_id', $id)->first();
            if ($giaoVien) {
                $lopHocCount = LopHoc::where('giao_vien_id', $giaoVien->id)->count();
                if ($lopHocCount > 0) {
                    return back()->with('error', 'Không thể xóa người dùng này vì đã được phân công lớp học!');
                }
                $giaoVien->delete();
            }
        } elseif ($vaiTroTen == 'tro_giang') {
            $troGiang = TroGiang::where('nguoi_dung_id', $id)->first();
            if ($troGiang) {
                $lopHocCount = LopHoc::where('tro_giang_id', $troGiang->id)->count();
                if ($lopHocCount > 0) {
                    return back()->with('error', 'Không thể xóa người dùng này vì đã được phân công lớp học!');
                }
                $troGiang->delete();
            }
        }
        
        // Xóa người dùng
        $nguoiDung->delete();
        
        return redirect()->route('admin.nguoi-dung.index')
            ->with('success', 'Xóa người dùng thành công');
    }
} 