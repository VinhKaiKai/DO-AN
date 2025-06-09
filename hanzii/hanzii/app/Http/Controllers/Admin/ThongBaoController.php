<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThongBaoLopHoc;
use App\Models\LopHoc;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ThongBaoController extends Controller
{
    /**
     * Hiển thị danh sách thông báo
     */
    public function index(Request $request)
    {
        $query = ThongBaoLopHoc::with(['lopHoc']);
        
        // Lọc theo lớp học
        if ($request->has('lop_hoc_id') && $request->lop_hoc_id != '') {
            $query->where('lop_hoc_id', $request->lop_hoc_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        // Lọc theo ngày tạo
        if ($request->has('tu_ngay') && $request->tu_ngay != '') {
            $query->whereDate('tao_luc', '>=', $request->tu_ngay);
        }
        
        if ($request->has('den_ngay') && $request->den_ngay != '') {
            $query->whereDate('tao_luc', '<=', $request->den_ngay);
        }
        
        $thongBaos = $query->orderBy('tao_luc', 'desc')->paginate(10);
        $lopHocs = LopHoc::orderBy('ten', 'asc')->get();
        
        return view('admin.thong-bao.index', compact('thongBaos', 'lopHocs'));
    }
    
    /**
     * Hiển thị form tạo thông báo
     */
    public function create()
    {
        $lopHocs = LopHoc::orderBy('ten', 'asc')->get();
        return view('admin.thong-bao.create', compact('lopHocs'));
    }
    
    /**
     * Lưu thông báo mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'tieu_de' => 'required|max:255',
            'noi_dung' => 'required',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'trang_thai' => 'required|in:0,1',
            'files.*' => 'nullable|file|max:10240', // Max 10MB
        ]);
        
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        $thongBao = new ThongBaoLopHoc();
        $thongBao->tieu_de = $validated['tieu_de'];
        $thongBao->noi_dung = $validated['noi_dung'];
        $thongBao->lop_hoc_id = $validated['lop_hoc_id'];
        $thongBao->trang_thai = $validated['trang_thai'];
        $thongBao->nguoi_tao = $nguoiDungId;
        $thongBao->save();
        
        // Xử lý upload files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $tenGoc = $file->getClientOriginalName();
                $tenLuu = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $duongDan = $file->storeAs('thong-bao', $tenLuu, 'public');
                
                // Lưu thông tin file vào database
                $fileModel = new Files();
                $fileModel->ten_goc = $tenGoc;
                $fileModel->ten_luu = $tenLuu;
                $fileModel->duong_dan = $duongDan;
                $fileModel->loai = $file->getClientMimeType();
                $fileModel->kich_thuoc = $file->getSize();
                $fileModel->nguoi_tao = $nguoiDungId;
                $fileModel->thong_bao_id = $thongBao->id;
                $fileModel->save();
            }
        }
        
        return redirect()->route('admin.thong-bao.index')
            ->with('success', 'Thông báo đã được tạo thành công.');
    }
    
    /**
     * Hiển thị chi tiết thông báo
     */
    public function show($id)
    {
        $thongBao = ThongBaoLopHoc::with(['lopHoc', 'files'])->findOrFail($id);
        return view('admin.thong-bao.show', compact('thongBao'));
    }
    
    /**
     * Hiển thị form chỉnh sửa thông báo
     */
    public function edit($id)
    {
        $thongBao = ThongBaoLopHoc::with(['files'])->findOrFail($id);
        $lopHocs = LopHoc::orderBy('ten', 'asc')->get();
        return view('admin.thong-bao.edit', compact('thongBao', 'lopHocs'));
    }
    
    /**
     * Cập nhật thông báo
     */
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'tieu_de' => 'required|max:255',
            'noi_dung' => 'required',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'trang_thai' => 'required|in:0,1',
            'files.*' => 'nullable|file|max:10240', // Max 10MB
        ]);
        
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        $thongBao = ThongBaoLopHoc::findOrFail($id);
        $thongBao->tieu_de = $validated['tieu_de'];
        $thongBao->noi_dung = $validated['noi_dung'];
        $thongBao->lop_hoc_id = $validated['lop_hoc_id'];
        $thongBao->trang_thai = $validated['trang_thai'];
        $thongBao->save();
        
        // Xử lý upload files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $tenGoc = $file->getClientOriginalName();
                $tenLuu = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $duongDan = $file->storeAs('thong-bao', $tenLuu, 'public');
                
                // Lưu thông tin file vào database
                $fileModel = new Files();
                $fileModel->ten_goc = $tenGoc;
                $fileModel->ten_luu = $tenLuu;
                $fileModel->duong_dan = $duongDan;
                $fileModel->loai = $file->getClientMimeType();
                $fileModel->kich_thuoc = $file->getSize();
                $fileModel->nguoi_tao = $nguoiDungId;
                $fileModel->thong_bao_id = $thongBao->id;
                $fileModel->save();
            }
        }
        
        return redirect()->route('admin.thong-bao.index')
            ->with('success', 'Thông báo đã được cập nhật thành công.');
    }
    
    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        $thongBao = ThongBaoLopHoc::findOrFail($id);
        
        // Xóa các file đính kèm
        $files = Files::where('thong_bao_id', $id)->get();
        foreach ($files as $file) {
            // Xóa file trên storage
            if (Storage::disk('public')->exists($file->duong_dan)) {
                Storage::disk('public')->delete($file->duong_dan);
            }
            // Xóa record trong database
            $file->delete();
        }
        
        // Xóa thông báo
        $thongBao->delete();
        
        return redirect()->route('admin.thong-bao.index')
            ->with('success', 'Thông báo đã được xóa thành công.');
    }
    
    /**
     * Tải xuống file đính kèm
     */
    public function downloadFile($id)
    {
        $file = Files::findOrFail($id);
        $path = storage_path('app/public/' . $file->duong_dan);
        
        return response()->download($path, $file->ten_goc);
    }
} 