<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiLieuBoTro;
use App\Models\BaiHoc;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaiLieuController extends Controller
{
    /**
     * Hiển thị danh sách tài liệu
     */
    public function index(Request $request)
    {
        $query = TaiLieuBoTro::with(['baiHoc', 'lopHoc', 'lopHoc.khoaHoc']);
        
        // Tìm kiếm theo từ khóa
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
        }
        
        // Lọc theo lớp học
        if ($request->has('lop_hoc_id') && !empty($request->lop_hoc_id)) {
            $query->where('lop_hoc_id', $request->lop_hoc_id);
        }
        
        // Lọc theo bài học
        if ($request->has('bai_hoc_id') && !empty($request->bai_hoc_id)) {
            $query->where('bai_hoc_id', $request->bai_hoc_id);
        }
        
        $taiLieus = $query->orderBy('tao_luc', 'desc')->paginate(15);
        
        // Lấy danh sách lớp học và bài học cho bộ lọc
        $lopHocs = LopHoc::orderBy('ten')->get();
        $baiHocs = BaiHoc::orderBy('ten')->get();
        
        return view('admin.tai-lieu.index', compact('taiLieus', 'lopHocs', 'baiHocs'));
    }

    /**
     * Hiển thị form tạo tài liệu mới
     */
    public function create()
    {
        $lopHocs = LopHoc::orderBy('ten')->get();
        $baiHocs = BaiHoc::orderBy('ten')->get();
        
        return view('admin.tai-lieu.create', compact('lopHocs', 'baiHocs'));
    }

    /**
     * Lưu tài liệu mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'bai_hoc_id' => 'required|exists:bai_hocs,id',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'file' => 'required|file|max:10240', // Tối đa 10MB
        ]);
        
        try {
            DB::beginTransaction();
            
            // Xử lý upload file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = Str::slug($validated['tieu_de']) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tai-lieu', $fileName, 'public');
                
                // Tạo tài liệu mới
                $taiLieu = new TaiLieuBoTro();
                $taiLieu->tieu_de = $validated['tieu_de'];
                $taiLieu->mo_ta = $validated['mo_ta'];
                $taiLieu->bai_hoc_id = $validated['bai_hoc_id'];
                $taiLieu->lop_hoc_id = $validated['lop_hoc_id'];
                $taiLieu->duong_dan_file = $path;
                $taiLieu->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.tai-lieu.index')
                ->with('success', 'Tạo tài liệu thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị thông tin chi tiết tài liệu
     */
    public function show($id)
    {
        $taiLieu = TaiLieuBoTro::with(['baiHoc', 'lopHoc', 'lopHoc.khoaHoc'])
            ->findOrFail($id);
            
        return view('admin.tai-lieu.show', compact('taiLieu'));
    }

    /**
     * Hiển thị form chỉnh sửa tài liệu
     */
    public function edit($id)
    {
        $taiLieu = TaiLieuBoTro::findOrFail($id);
        $lopHocs = LopHoc::orderBy('ten')->get();
        $baiHocs = BaiHoc::orderBy('ten')->get();
        
        return view('admin.tai-lieu.edit', compact('taiLieu', 'lopHocs', 'baiHocs'));
    }

    /**
     * Cập nhật tài liệu
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'bai_hoc_id' => 'required|exists:bai_hocs,id',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'file' => 'nullable|file|max:10240', // Tối đa 10MB
        ]);
        
        try {
            DB::beginTransaction();
            
            $taiLieu = TaiLieuBoTro::findOrFail($id);
            $taiLieu->tieu_de = $validated['tieu_de'];
            $taiLieu->mo_ta = $validated['mo_ta'];
            $taiLieu->bai_hoc_id = $validated['bai_hoc_id'];
            $taiLieu->lop_hoc_id = $validated['lop_hoc_id'];
            
            // Xử lý upload file nếu có
            if ($request->hasFile('file')) {
                // Xóa file cũ
                if ($taiLieu->duong_dan_file && Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
                    Storage::disk('public')->delete($taiLieu->duong_dan_file);
                }
                
                // Upload file mới
                $file = $request->file('file');
                $fileName = Str::slug($validated['tieu_de']) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tai-lieu', $fileName, 'public');
                
                $taiLieu->duong_dan_file = $path;
            }
            
            $taiLieu->save();
            
            DB::commit();
            
            return redirect()->route('admin.tai-lieu.index')
                ->with('success', 'Cập nhật tài liệu thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xóa tài liệu
     */
    public function destroy($id)
    {
        try {
            $taiLieu = TaiLieuBoTro::findOrFail($id);
            
            // Xóa file
            if ($taiLieu->duong_dan_file && Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
                Storage::disk('public')->delete($taiLieu->duong_dan_file);
            }
            
            $taiLieu->delete();
            
            return redirect()->route('admin.tai-lieu.index')
                ->with('success', 'Xóa tài liệu thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Tải xuống tài liệu
     */
    public function download($id)
    {
        $taiLieu = TaiLieuBoTro::findOrFail($id);
        
        if (!$taiLieu->duong_dan_file || !Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
            return back()->with('error', 'File không tồn tại');
        }
        
        $filePath = Storage::disk('public')->path($taiLieu->duong_dan_file);
        $fileName = $taiLieu->tieu_de . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        
        return response()->download($filePath, $fileName);
    }
} 