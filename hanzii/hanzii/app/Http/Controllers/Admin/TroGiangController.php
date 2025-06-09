<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\TroGiang;
use App\Models\VaiTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TroGiangController extends Controller
{
    /**
     * Hiển thị danh sách trợ giảng
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = TroGiang::with(['nguoiDung.vaiTro'])
            ->whereHas('nguoiDung', function($query) {
                $query->where('vai_tro_id', function($q) {
                    $q->select('id')
                      ->from('vai_tros')
                      ->where('ten', 'tro_giang');
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
        $sortField = $request->sort ?? 'nguoi_dung_id';
        $sortDirection = $request->direction ?? 'desc';
        
        if ($sortField === 'ho_ten') {
            $query->join('nguoi_dungs', 'tro_giangs.nguoi_dung_id', '=', 'nguoi_dungs.id')
                  ->orderBy('nguoi_dungs.ho', $sortDirection)
                  ->orderBy('nguoi_dungs.ten', $sortDirection)
                  ->select('tro_giangs.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $troGiangs = $query->paginate(10);
        
        return view('admin.tro-giang.index', compact('troGiangs'));
    }
    
    /**
     * Hiển thị form tạo trợ giảng mới
     */
    public function create()
    {
        return view('admin.tro-giang.create');
    }
    
    /**
     * Lưu thông tin trợ giảng mới
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
            $nguoiDung->mat_khau = Hash::make('trogiangtiengtrunglythu');
            $nguoiDung->loai_tai_khoan = 'tro_giang';
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();
            
            // Tạo trợ giảng
            $troGiang = new TroGiang();
            $troGiang->nguoi_dung_id = $nguoiDung->id;
            $troGiang->bang_cap = $request->bang_cap;
            $troGiang->chuyen_mon = $request->chuyen_mon;
            $troGiang->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
            $troGiang->save();
            
            // Liên kết với vai trò trợ giảng
            $vaiTroTroGiang = VaiTro::where('ten', 'tro_giang')->first();
            if ($vaiTroTroGiang) {
                DB::table('vai_tro_nguoi_dungs')->insert([
                    'nguoi_dung_id' => $nguoiDung->id,
                    'vai_tro_id' => $vaiTroTroGiang->id,
                    'tao_luc' => now(),
                    'cap_nhat_luc' => now(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.tro-giang.index')
                ->with('success', 'Thêm trợ giảng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết trợ giảng
     */
    public function show($id)
    {
        $troGiang = TroGiang::with(['nguoiDung', 'lopHocs'])->findOrFail($id);
        return view('admin.tro-giang.show', compact('troGiang'));
    }
    
    /**
     * Hiển thị form chỉnh sửa trợ giảng
     */
    public function edit($id)
    {
        $troGiang = TroGiang::with('nguoiDung')->findOrFail($id);
        return view('admin.tro-giang.edit', compact('troGiang'));
    }
    
    /**
     * Cập nhật thông tin trợ giảng
     */
    public function update(Request $request, $id)
    {
        $troGiang = TroGiang::findOrFail($id);
        
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:nguoi_dungs,email,' . $troGiang->nguoi_dung_id,
            'so_dien_thoai' => 'required|string|unique:nguoi_dungs,so_dien_thoai,' . $troGiang->nguoi_dung_id,
            'dia_chi' => 'nullable|string',
            'bang_cap' => 'required|string',
            'chuyen_mon' => 'required|string',
            'so_nam_kinh_nghiem' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Cập nhật thông tin người dùng
            $nguoiDung = $troGiang->nguoiDung;
            $nguoiDung->ho = $request->ho;
            $nguoiDung->ten = $request->ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
            $nguoiDung->dia_chi = $request->dia_chi;
            $nguoiDung->save();
            
            // Cập nhật thông tin trợ giảng
            $troGiang->bang_cap = $request->bang_cap;
            $troGiang->chuyen_mon = $request->chuyen_mon;
            $troGiang->so_nam_kinh_nghiem = $request->so_nam_kinh_nghiem;
            $troGiang->save();
            
            DB::commit();
            
            return redirect()->route('admin.tro-giang.show', $troGiang->id)
                ->with('success', 'Cập nhật trợ giảng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Xóa trợ giảng
     */
    public function destroy($id)
    {
        $troGiang = TroGiang::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Kiểm tra nếu trợ giảng đang phụ trách lớp học nào
            if ($troGiang->lopHocs()->count() > 0) {
                return back()->withErrors(['msg' => 'Không thể xóa trợ giảng này vì đang phụ trách lớp học.']);
            }
            
            // Xóa thông tin liên kết vai trò
            DB::table('vai_tro_nguoi_dungs')->where('nguoi_dung_id', $troGiang->nguoi_dung_id)->delete();
            
            // Lấy id người dùng trước khi xóa trợ giảng
            $nguoiDungId = $troGiang->nguoi_dung_id;
            
            // Xóa trợ giảng
            $troGiang->delete();
            
            // Xóa người dùng
            NguoiDung::destroy($nguoiDungId);
            
            DB::commit();
            
            return redirect()->route('admin.tro-giang.index')
                ->with('success', 'Đã xóa trợ giảng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
} 