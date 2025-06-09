<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\GiaoVien;
use App\Models\VaiTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GiaoVienController extends Controller
{
    /**
     * Hiển thị danh sách giáo viên
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = GiaoVien::with(['nguoiDung.vaiTro'])
            ->whereHas('nguoiDung', function($query) {
                $query->where('vai_tro_id', function($q) {
                    $q->select('id')
                      ->from('vai_tros')
                      ->where('ten', 'giao_vien');
                });
            });
        
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
            $query->join('nguoi_dungs', 'giao_viens.nguoi_dung_id', '=', 'nguoi_dungs.id')
                  ->orderBy('nguoi_dungs.ho', $sortDirection)
                  ->orderBy('nguoi_dungs.ten', $sortDirection)
                  ->select('giao_viens.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $giaoViens = $query->paginate(10);
        
        return view('admin.giao-vien.index', compact('giaoViens'));
    }
    
    /**
     * Hiển thị form tạo giáo viên mới
     */
    public function create()
    {
        return view('admin.giao-vien.create');
    }
    
    /**
     * Lưu thông tin giáo viên mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:nguoi_dungs,email',
            'so_dien_thoai' => 'required|string|unique:nguoi_dungs,so_dien_thoai',
            'dia_chi' => 'nullable|string',
            'bang_cap' => 'required|string',
            'chuyen_mon' => 'required|string',
            'so_nam_kinh_nghiem' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Tạo người dùng mới
            $nguoiDung = new NguoiDung();
            $nguoiDung->ho = $request->ho;
            $nguoiDung->ten = $request->ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
            $nguoiDung->mat_khau = Hash::make('giaovientiengtrunglythu');
            $nguoiDung->loai_tai_khoan = 'giao_vien';
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();
            
            // Tạo giáo viên
            $giaoVien = new GiaoVien();
            $giaoVien->nguoi_dung_id = $nguoiDung->id;
            $giaoVien->bang_cap = $request->bang_cap;
            $giaoVien->chuyen_mon = $request->chuyen_mon;
            $giaoVien->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
            $giaoVien->save();
            
            // Liên kết với vai trò giáo viên
            $vaiTroGiaoVien = VaiTro::where('ten', 'giao_vien')->first();
            if ($vaiTroGiaoVien) {
                DB::table('vai_tro_nguoi_dungs')->insert([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'vai_tro_id' => $vaiTroGiaoVien->id,
                    'tao_luc' => now(),
                    'cap_nhat_luc' => now(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.giao-vien.index')
                ->with('success', 'Thêm giáo viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết giáo viên
     */
    public function show($id)
    {
        $giaoVien = GiaoVien::with(['nguoiDung', 'lopHocs'])->findOrFail($id);
        return view('admin.giao-vien.show', compact('giaoVien'));
    }
    
    /**
     * Hiển thị form chỉnh sửa giáo viên
     */
    public function edit($id)
    {
        $giaoVien = GiaoVien::with('nguoiDung')->findOrFail($id);
        return view('admin.giao-vien.edit', compact('giaoVien'));
    }
    
    /**
     * Cập nhật thông tin giáo viên
     */
    public function update(Request $request, $id)
    {
        $giaoVien = GiaoVien::findOrFail($id);
        
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:nguoi_dungs,email,' . $giaoVien->nguoi_dung_id,
            'so_dien_thoai' => 'required|string|unique:nguoi_dungs,so_dien_thoai,' . $giaoVien->nguoi_dung_id,
            'dia_chi' => 'nullable|string',
            'bang_cap' => 'required|string',
            'chuyen_mon' => 'required|string',
            'so_nam_kinh_nghiem' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Cập nhật thông tin người dùng
            $nguoiDung = $giaoVien->nguoiDung;
            $nguoiDung->ho = $request->ho;
            $nguoiDung->ten = $request->ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();
            
            // Cập nhật thông tin giáo viên
            $giaoVien->bang_cap = $request->bang_cap;
            $giaoVien->chuyen_mon = $request->chuyen_mon;
            $giaoVien->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
            $giaoVien->save();
            
            DB::commit();
            
            return redirect()->route('admin.giao-vien.show', $giaoVien->id)
                ->with('success', 'Cập nhật giáo viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Xóa giáo viên
     */
    public function destroy($id)
    {
        $giaoVien = GiaoVien::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Kiểm tra nếu giáo viên đang phụ trách lớp học nào
            if ($giaoVien->lopHocs()->count() > 0) {
                return back()->withErrors(['msg' => 'Không thể xóa giáo viên này vì đang phụ trách lớp học.']);
            }
            
            // Xóa thông tin liên kết vai trò
            DB::table('vai_tro_nguoi_dungs')->where('nguoi_dung_id', $giaoVien->nguoi_dung_id)->delete();
            
            // Lấy id người dùng trước khi xóa giáo viên
            $nguoiDungId = $giaoVien->nguoi_dung_id;
            
            // Xóa giáo viên
            $giaoVien->delete();
            
            // Xóa người dùng
            NguoiDung::destroy($nguoiDungId);
            
            DB::commit();
            
            return redirect()->route('admin.giao-vien.index')
                ->with('success', 'Đã xóa giáo viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
} 