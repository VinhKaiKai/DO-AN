<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiHoc;
use App\Models\LopHoc;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BaiHocController extends Controller
{
    /**
     * Hiển thị danh sách bài học
     */
    public function index(Request $request)
    {
        // Xử lý tìm kiếm và lọc dữ liệu
        $query = BaiHoc::query();
        
        if ($request->has('khoa_hoc_id') && !empty($request->khoa_hoc_id)) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('tieu_de', 'like', "%{$search}%");
        }
        
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        $baiHocs = $query->with('khoaHoc')->orderBy('tao_luc', 'desc')->paginate(10);
        
        return view('admin.bai-hoc.index', compact('baiHocs'));
    }

    /**
     * Hiển thị form tạo bài học mới
     */
    public function create(Request $request)
    {
        $khoaHocId = $request->input('khoa_hoc_id');
        $khoaHoc = null;
        
        if ($khoaHocId) {
            $khoaHoc = KhoaHoc::findOrFail($khoaHocId);
        } else {
            // Nếu không có khóa học, chuyển hướng về trang danh sách khóa học
            return redirect()->route('admin.khoa-hoc.index')
                ->with('warning', 'Vui lòng chọn khóa học trước khi thêm bài học mới');
        }
        
        // Không cần lấy danh sách khóa học khi đã có khóa học cụ thể
        return view('admin.bai-hoc.create', compact('khoaHoc', 'khoaHocId'));
    }

    /**
     * Lưu bài học mới vào database
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'khoa_hoc_id' => 'required|exists:khoa_hocs,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'so_thu_tu' => 'required|integer|min:1',
            'thoi_luong' => 'required|integer|min:1',
            'loai' => 'required|in:video,van_ban',
            'url_video' => 'nullable|string|max:255',
            'trang_thai' => 'required|in:chua_xuat_ban,da_xuat_ban',
            'tai_lieu' => 'nullable|array',
            'tai_lieu.*' => 'file|max:10240',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Tạo bài học mới
            $baiHoc = new BaiHoc();
            $baiHoc->khoa_hoc_id = $validated['khoa_hoc_id'];
            $baiHoc->tieu_de = $validated['tieu_de'];
            $baiHoc->noi_dung = $validated['noi_dung'];
            $baiHoc->so_thu_tu = $validated['so_thu_tu'];
            $baiHoc->thoi_luong = $validated['thoi_luong'];
            $baiHoc->loai = $validated['loai'];
            $baiHoc->url_video = $validated['url_video'] ?? null;
            $baiHoc->trang_thai = $validated['trang_thai'];
            $baiHoc->save();
            
            // Xử lý tệp đính kèm nếu có
            if ($request->hasFile('tai_lieu')) {
                foreach ($request->file('tai_lieu') as $file) {
                    $path = $file->store('tai-lieu', 'public');
                    
                    $taiLieu = new \App\Models\TaiLieuBoTro();
                    $taiLieu->ten = $file->getClientOriginalName();
                    $taiLieu->tieu_de = $file->getClientOriginalName();
                    $taiLieu->mo_ta = 'Tài liệu bổ trợ cho bài học';
                    $taiLieu->duong_dan_file = $path;
                    $taiLieu->bai_hoc_id = $baiHoc->id;
                    $taiLieu->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.bai-hoc.show', $baiHoc->id)
                ->with('success', 'Bài học đã được tạo thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị chi tiết bài học
     */
    public function show($id)
    {
        $baiHoc = BaiHoc::with(['khoaHoc', 'taiLieuBoTros', 'baiTaps'])->findOrFail($id);
        
        return view('admin.bai-hoc.show', compact('baiHoc'));
    }

    /**
     * Hiển thị form chỉnh sửa bài học
     */
    public function edit($id)
    {
        $baiHoc = BaiHoc::with(['khoaHoc', 'taiLieuBoTros'])->findOrFail($id);
        $khoaHocs = KhoaHoc::where('trang_thai', 'hoat_dong')->orderBy('ten')->get();
        
        return view('admin.bai-hoc.edit', compact('baiHoc', 'khoaHocs'));
    }

    /**
     * Cập nhật thông tin bài học
     */
    public function update(Request $request, $id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'khoa_hoc_id' => 'required|exists:khoa_hocs,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'so_thu_tu' => 'required|integer|min:1',
            'thoi_luong' => 'required|integer|min:1',
            'loai' => 'required|in:video,van_ban',
            'url_video' => 'nullable|string|max:255',
            'trang_thai' => 'required|in:chua_xuat_ban,da_xuat_ban',
            'tai_lieu' => 'nullable|array',
            'tai_lieu.*' => 'file|max:10240',
            'xoa_tai_lieu' => 'nullable|array',
            'xoa_tai_lieu.*' => 'exists:tai_lieu_bo_tros,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Cập nhật thông tin bài học
            $baiHoc->khoa_hoc_id = $validated['khoa_hoc_id'];
            $baiHoc->tieu_de = $validated['tieu_de'];
            $baiHoc->noi_dung = $validated['noi_dung'];
            $baiHoc->so_thu_tu = $validated['so_thu_tu'];
            $baiHoc->thoi_luong = $validated['thoi_luong'];
            $baiHoc->loai = $validated['loai'];
            $baiHoc->url_video = $validated['url_video'] ?? null;
            $baiHoc->trang_thai = $validated['trang_thai'];
            $baiHoc->save();
            
            // Xử lý tệp đính kèm nếu có
            if ($request->hasFile('tai_lieu')) {
                foreach ($request->file('tai_lieu') as $file) {
                    $path = $file->store('tai-lieu', 'public');
                    
                    $taiLieu = new \App\Models\TaiLieuBoTro();
                    $taiLieu->ten = $file->getClientOriginalName();
                    $taiLieu->tieu_de = $file->getClientOriginalName();
                    $taiLieu->mo_ta = 'Tài liệu bổ trợ cho bài học';
                    $taiLieu->duong_dan_file = $path;
                    $taiLieu->bai_hoc_id = $baiHoc->id;
                    $taiLieu->save();
                }
            }
            
            // Xóa tài liệu nếu có yêu cầu
            if ($request->has('xoa_tai_lieu')) {
                foreach ($request->xoa_tai_lieu as $taiLieuId) {
                    $taiLieu = \App\Models\TaiLieuBoTro::find($taiLieuId);
                    if ($taiLieu && $taiLieu->bai_hoc_id == $baiHoc->id) {
                        Storage::disk('public')->delete($taiLieu->duong_dan_file);
                        $taiLieu->delete();
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.bai-hoc.show', $baiHoc->id)
                ->with('success', 'Bài học đã được cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xóa bài học
     */
    public function destroy($id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Xóa tài liệu đính kèm
            foreach ($baiHoc->taiLieuBoTros as $taiLieu) {
                Storage::disk('public')->delete($taiLieu->duong_dan_file);
                $taiLieu->delete();
            }
            
            // Xóa các bài tập liên quan
            foreach ($baiHoc->baiTaps as $baiTap) {
                // Xử lý thêm nếu cần
                $baiTap->delete();
            }
            
            // Xóa bài học
            $baiHoc->delete();
            
            DB::commit();
            
            return redirect()->route('admin.bai-hoc.index')
                ->with('success', 'Bài học đã được xóa thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa tài liệu bổ trợ
     */
    public function xoaTaiLieu($id)
    {
        try {
            $taiLieu = \App\Models\TaiLieuBoTro::findOrFail($id);
            $baiHocId = $taiLieu->bai_hoc_id;
            
            // Xóa file vật lý
            Storage::disk('public')->delete($taiLieu->duong_dan_file);
            
            // Xóa record trong database
            $taiLieu->delete();
            
            return redirect()->route('admin.bai-hoc.show', $baiHocId)
                ->with('success', 'Tài liệu đã được xóa thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 