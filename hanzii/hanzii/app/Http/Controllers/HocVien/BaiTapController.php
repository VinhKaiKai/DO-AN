<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\BaiTap;
use App\Models\BaiTuLuan;
use App\Models\FileBaiTap;
use App\Models\HocVien;
use App\Models\LichSuLamBai;
use App\Models\BaiTapDaNop;
use App\Models\ChiTietCauTraLoi;
use App\Models\CauHoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BaiTapController extends Controller
{
    /**
     * Hiển thị chi tiết bài tập
     */
    public function show(Request $request, $id)
    {
        $baiTap = BaiTap::with(['baiHoc.baiHocLops.lopHoc'])->findOrFail($id);
        
        // Kiểm tra quyền truy cập
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        $lopHocIds = $baiTap->baiHoc->baiHocLops->pluck('lop_hoc_id')->toArray();
        
     
        
        // Lấy kết quả đã làm (nếu có)
        $baiTapDaNop = BaiTapDaNop::where('bai_tap_id', $id)
            ->where('hoc_vien_id', $hocVien->id)
            ->latest('ngay_nop')
            ->first();
        
        // Lấy lớp học từ bài học
        $lopHoc = $baiTap->baiHoc->baiHocLops->first()->lopHoc;
        
        return view('hoc-vien.bai-tap.show', compact(
            'baiTap',
            'baiTapDaNop',
            'lopHoc'
        ));
    }
    
    /**
     * Hiển thị form nộp bài tập (tự luận hoặc file)
     */
    public function formNopBai($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        $baiTap = BaiTap::with(['baiHoc', 'baiHoc.baiHocLops.lopHoc'])->findOrFail($id);
        
        // Kiểm tra bài học tồn tại
        if (!$baiTap->baiHoc) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bài học');
        }
        
        // Lấy lớp học từ bài học
        $baiHocLop = $baiTap->baiHoc->baiHocLops->first();
        if (!$baiHocLop) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin lớp học');
        }
        
        $lopHoc = $baiHocLop->lopHoc;
        if (!$lopHoc) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin lớp học');
        }
        
     
        
        $baiTapDaNop = BaiTapDaNop::where('bai_tap_id', $baiTap->id)
            ->where('hoc_vien_id', $hocVien->id)
            ->first();
        
        if ($baiTapDaNop && $baiTapDaNop->trang_thai !== 'chua_hoan_thanh') {
            return redirect()->route('hoc-vien.bai-tap.ket-qua', $baiTapDaNop->id)
                ->with('error', 'Bạn đã nộp bài tập này');
        }
        
        if ($baiTap->han_nop && now() > $baiTap->han_nop) {
            return redirect()->route('hoc-vien.bai-tap.show', $baiTap->id)
                ->with('error', 'Đã quá hạn nộp bài tập');
        }
        
        return view('hoc-vien.bai-tap.nop-bai', compact('baiTap', 'baiTapDaNop', 'lopHoc'));
    }
    
    /**
     * Xử lý nộp bài tập (tự luận hoặc file)
     */
    public function nopBai(Request $request, $id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        $baiTap = BaiTap::with(['baiHoc', 'baiHoc.baiHocLops.lopHoc'])->findOrFail($id);
        
        // Kiểm tra bài học tồn tại
        if (!$baiTap->baiHoc) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bài học');
        }
        
        // Lấy lớp học từ bài học
        $baiHocLop = $baiTap->baiHoc->baiHocLops->first();
        if (!$baiHocLop) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin lớp học');
        }
        
        $lopHoc = $baiHocLop->lopHoc;
        if (!$lopHoc) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin lớp học');
        }
      
        // Kiểm tra hạn nộp
        if ($baiTap->han_nop && now() > $baiTap->han_nop) {
            return redirect()->route('hoc-vien.bai-tap.show', $baiTap->id)
                ->with('error', 'Bài tập đã hết hạn nộp.');
        }
        
        // Kiểm tra bài tập đã nộp chưa
        $baiTapDaNop = BaiTapDaNop::where('bai_tap_id', $baiTap->id)
            ->where('hoc_vien_id', $hocVien->id)
            ->first();
        
        // Force nộp lại bài nếu có tham số is_new_submission    
        $forceNewSubmission = $request->has('is_new_submission');
            
        if ($baiTapDaNop && !$forceNewSubmission) {
            return redirect()->route('hoc-vien.bai-tap.show', $id)
                ->with('warning', 'Bạn đã nộp bài tập này rồi.');
        }
        
        // Validate dữ liệu đầu vào
        if ($baiTap->loai == 'tu_luan') {
            $validator = Validator::make($request->all(), [
                'noi_dung' => 'required|string',
            ]);
        } else { // file
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // 10MB
            ]);
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Xóa bài tập cũ nếu là nộp lại bài
            if ($baiTapDaNop && $forceNewSubmission) {
                // Xóa file cũ nếu có
                if ($baiTapDaNop->file_path && Storage::disk('public')->exists($baiTapDaNop->file_path)) {
                    Storage::disk('public')->delete($baiTapDaNop->file_path);
                }
                
                // Log lại việc xóa bài cũ
                Log::info('Xóa bài tập cũ ID: ' . $baiTapDaNop->id . ' của học viên ID: ' . $hocVien->id);
                
                // Option 1: Xóa bài cũ
                // $baiTapDaNop->delete();
                // $baiTapDaNop = new BaiTapDaNop();
                
                // Option 2: Cập nhật bài cũ
                $baiTapDaNop->ngay_nop = now();
                $baiTapDaNop->trang_thai = 'da_nop';
            } else {
                // Tạo bài tập đã nộp mới
                $baiTapDaNop = new BaiTapDaNop();
                $baiTapDaNop->bai_tap_id = $id;
                $baiTapDaNop->hoc_vien_id = $hocVien->id;
                $baiTapDaNop->ngay_nop = now();
                $baiTapDaNop->trang_thai = 'da_nop'; // Chờ giáo viên chấm
            }
            
            // Xử lý theo loại bài tập
            if ($baiTap->loai == 'tu_luan') {
                $baiTapDaNop->noi_dung = $request->input('noi_dung');
            } else { // file
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    
                    // Thêm timestamp vào tên file để tránh cache
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $timestamp = time();
                    $newFilename = $filename . '_' . $timestamp . '.' . $extension;
                    
                    $path = $file->storeAs('bai-tap-nop', $newFilename, 'public');
                    $baiTapDaNop->file_path = $path;
                    $baiTapDaNop->ten_file = $file->getClientOriginalName();
                }
            }
            
            $baiTapDaNop->save();
            
            // Tạo lịch sử làm bài
            $lichSuLamBai = new LichSuLamBai();
            $lichSuLamBai->hoc_vien_id = $hocVien->id;
            $lichSuLamBai->bai_tap_id = $id;
            $lichSuLamBai->ngay_lam = now();
            $lichSuLamBai->lop_hoc_id = $lopHoc->id;
            $lichSuLamBai->save();
            
            DB::commit();
            
            return redirect()->route('hoc-vien.bai-tap.show', $id)
                ->with('success', 'Bạn đã nộp bài tập thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi nộp bài tập: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị kết quả bài tập
     */
    public function ketQua($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        $baiTapDaNop = BaiTapDaNop::with(['baiTap'])->findOrFail($id);
            
        // Kiểm tra quyền truy cập
        if ($baiTapDaNop->hoc_vien_id != $hocVien->id) {
            return redirect()->route('hoc-vien.lop-hoc.index')
                ->with('error', 'Bạn không có quyền xem kết quả này.');
        }
        
        // Lấy thông tin bài tập
        $baiTap = $baiTapDaNop->baiTap;
        $lopHoc = $baiTap->baiHoc->lopHoc;
        
        return view('hoc-vien.bai-tap.ket-qua', compact('baiTapDaNop', 'baiTap', 'lopHoc'));
    }
    
    /**
     * Tải xuống file bài tập đã nộp
     *
     * @param int $id ID của bài tập đã nộp
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id)
    {
        // Kiểm tra quyền truy cập
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin học viên');
        }

        // Lấy thông tin bài tập đã nộp
        $baiTapDaNop = BaiTapDaNop::findOrFail($id);
        
        // Kiểm tra quyền truy cập (chỉ học viên nộp bài hoặc giáo viên của lớp mới có quyền tải)
        if ($baiTapDaNop->hoc_vien_id != $hocVien->id) {
            $baiTap = BaiTap::find($baiTapDaNop->bai_tap_id);
            
            // Kiểm tra xem bài tập có thuộc lớp học của học viên không
            $lopHocIds = $hocVien->dangKyHocs->pluck('lop_hoc_id')->toArray();
            $baiHocLopIds = $baiTap->baiHoc->baiHocLops->pluck('lop_hoc_id')->toArray();
            
            if (!array_intersect($lopHocIds, $baiHocLopIds)) {
                return redirect()->route('hoc-vien.lop-hoc.index')
                    ->with('error', 'Bạn không có quyền tải file này');
            }
        }
        
        // Kiểm tra file tồn tại
        if (!$baiTapDaNop->file_path || !Storage::disk('public')->exists($baiTapDaNop->file_path)) {
            return back()->with('error', 'File không tồn tại hoặc đã bị xóa');
        }
        
        // Trả về file để tải xuống
        return Storage::disk('public')->download(
            $baiTapDaNop->file_path, 
            $baiTapDaNop->ten_file ?? 'bai-tap-' . $id . '.file'
        );
    }
} 