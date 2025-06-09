<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\BaiHoc;
use App\Models\BaiHocLop;
use App\Models\BaiTap;
use App\Models\BinhLuan;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\TienDoBaiHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\BaiTapDaNop;
use App\Models\TaiLieuBoTro;
use App\Models\TienDoHocTap;

class BaiHocController extends Controller
{
    /**
     * Hiển thị nội dung bài học và bình luận
     *
     * @param  int  $lopHocId ID của lớp học
     * @param  int  $baiHocId ID của bài học
     * @return \Illuminate\Http\Response
     */
    public function show($lopHocId, $baiHocId)
    {
        // Lấy thông tin học viên từ người dùng đăng nhập
        $hocVien = HocVien::where('nguoi_dung_id', session('nguoi_dung_id'))->first();
        
        // Lấy thông tin lớp học
        $lopHoc = LopHoc::findOrFail($lopHocId);
        
        // Lấy thông tin bài học trong lớp
        $baiHocLop = BaiHocLop::where('bai_hoc_id', $baiHocId)
            ->where('lop_hoc_id', $lopHocId)
            ->first();
        
        if (!$baiHocLop) {
            return redirect()->route('hoc-vien.lop-hoc.show', $lopHocId)
                ->with('error', 'Bài học không thuộc lớp học này');
        }

        // Lấy tiến độ học tập
        $tienDo = TienDoBaiHoc::where('hoc_vien_id', $hocVien->id)
            ->where('bai_hoc_id', $baiHocId)
            ->first();
        
        // Kiểm tra xem đã hoàn thành bài học trước đó chưa
        $daHoanThanhBaiHocTruoc = true;
        
        if ($baiHocLop->so_thu_tu > 1) {
            // Tìm bài học trước đó
            $baiHocTruoc = BaiHocLop::where('lop_hoc_id', $lopHocId)
                ->where('so_thu_tu', $baiHocLop->so_thu_tu - 1)
                ->first();
            
            if ($baiHocTruoc) {
                $tienDoTruoc = TienDoBaiHoc::where('hoc_vien_id', $hocVien->id)
                    ->where('bai_hoc_id', $baiHocTruoc->bai_hoc_id)
                    ->where('trang_thai', 'da_hoan_thanh')
                    ->first();
                
                $daHoanThanhBaiHocTruoc = $tienDoTruoc ? true : false;
            }
        }
        
        // Lấy thông tin bài học chi tiết cùng bình luận
        $baiHoc = BaiHoc::with([
            'baiTaps.baiTapDaNops' => function ($query) use ($hocVien) {
                $query->where('hoc_vien_id', $hocVien->id);
            },
            'binhLuans.nguoiDung.vaiTro', // Thêm quan hệ bình luận và người dùng
        ])->findOrFail($baiHocId);
        
        // Lấy danh sách tài liệu bổ trợ của bài học
        $taiLieuBoTros = TaiLieuBoTro::where(function($query) use ($lopHocId, $baiHocId) {
                // Lấy tài liệu cho bài học này trong lớp học cụ thể
                $query->where('bai_hoc_id', $baiHocId)
                      ->where('lop_hoc_id', $lopHocId);
            })
            ->orWhere(function($query) use ($baiHocId) {
                // Lấy tài liệu chung cho bài học này (không gắn với lớp cụ thể)
                $query->where('bai_hoc_id', $baiHocId)
                      ->whereNull('lop_hoc_id');
            })
            ->orderBy('tao_luc', 'desc')
            ->get();
            
        // Log thông tin để debug
        Log::info('Đang lấy tài liệu cho bài học #' . $baiHocId . ' lớp #' . $lopHocId);
        Log::info('Số lượng tài liệu tìm thấy: ' . $taiLieuBoTros->count());
        
        // Thử lấy tất cả tài liệu liên quan đến bài học này
        $allTaiLieu = TaiLieuBoTro::where('bai_hoc_id', $baiHocId)->get();
        Log::info('Tổng số tài liệu cho bài học này (bất kể lớp nào): ' . $allTaiLieu->count());
        
        if ($allTaiLieu->count() > 0 && $taiLieuBoTros->count() == 0) {
            // Nếu có tài liệu nhưng không lấy được, gán lại
            $taiLieuBoTros = $allTaiLieu;
            Log::info('Đã gán lại tài liệu từ truy vấn đơn giản hơn');
        }
        
        // Tạo mảng bài tập đã nộp để dễ truy cập trong view
        $baiTapDaNop = [];
        if ($baiHoc->baiTaps) {
            foreach ($baiHoc->baiTaps as $baiTap) {
                if ($baiTap->baiTapDaNops && count($baiTap->baiTapDaNops) > 0) {
                    $baiTapDaNop[$baiTap->id] = $baiTap->baiTapDaNops[0]; // Lấy bài nộp mới nhất
                }
            }
        }
        // Lấy danh sách bài học của lớp
        $danhSachBaiHoc = BaiHocLop::where('lop_hoc_id', $lopHocId)
            ->orderBy('so_thu_tu', 'asc')
            ->with('baiHoc')
            ->get();
   
        // Lấy tiến độ của tất cả bài học trong lớp
        $tienDoBaiHocs = [];
        $danhSachTienDo = TienDoBaiHoc::where('hoc_vien_id', $hocVien->id)
            ->whereIn('bai_hoc_id', $danhSachBaiHoc->pluck('bai_hoc_id'))
            ->get();
            
        foreach ($danhSachTienDo as $td) {
            $tienDoBaiHocs[$td->bai_hoc_id] = $td;
        }
   
        // Nếu chưa có tiến độ, tạo mới
        if (!$tienDo) {
            $tienDo = new TienDoBaiHoc();
            $tienDo->hoc_vien_id = $hocVien->id;
            $tienDo->bai_hoc_id = $baiHocId;
            $tienDo->trang_thai = 'da_bat_dau';
            $tienDo->save();
        }
        
        return view('hoc-vien.bai-hoc.show', compact(
            'baiHoc',
            'lopHoc',
            'tienDo',
            'baiHocLop',
            'daHoanThanhBaiHocTruoc',
            'danhSachBaiHoc',
            'tienDoBaiHocs',
            'baiTapDaNop',
            'taiLieuBoTros'
        ));
    }
    
    /**
     * Cập nhật tiến độ bài học
     */
    public function capNhatTienDo(Request $request, $lopHocId, $baiHocId)
    {
        // Lấy thông tin người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
        // Kiểm tra tất cả bài tập đã được hoàn thành chưa
        $baiHoc = BaiHoc::with(['baiTaps.baiTapDaNops' => function ($query) use ($hocVien) {
            $query->where('hoc_vien_id', $hocVien->id);
        }])->findOrFail($baiHocId);
        
        $tongSoBaiTap = count($baiHoc->baiTaps);
        $baiTapDaHoanThanh = 0;
        
        foreach($baiHoc->baiTaps as $baiTap) {
            $daNop = false;
            if(isset($baiTap->baiTapDaNops) && $baiTap->baiTapDaNops->isNotEmpty()) {
                $daNop = true;
            }
            if($daNop) {
                $baiTapDaHoanThanh++;
            }
        }
        
        // Nếu có bài tập mà chưa hoàn thành hết, không cho phép đánh dấu hoàn thành
        if ($tongSoBaiTap > 0 && $baiTapDaHoanThanh < $tongSoBaiTap) {
            return redirect()->back()->with('error', "Vui lòng hoàn thành tất cả bài tập trước khi đánh dấu hoàn thành bài học ($baiTapDaHoanThanh/$tongSoBaiTap bài tập)");
        }
     
        // Cập nhật tiến độ
        $tienDo = TienDoBaiHoc::where('hoc_vien_id', $hocVien->id)
                    ->where('bai_hoc_id', $baiHocId)
                    ->first();
                    
        if (!$tienDo) {
            $tienDo = new TienDoBaiHoc();
            $tienDo->hoc_vien_id = $hocVien->id;
            $tienDo->bai_hoc_id = $baiHocId;
            $tienDo->ngay_bat_dau = now();
        }
        
        $tienDo->trang_thai = 'da_hoan_thanh';
        $tienDo->ngay_hoan_thanh = now();
        $tienDo->save();
        
        // Kiểm tra và cập nhật bài học tiếp theo
        $baiHocTiepTheo = BaiHocLop::where('lop_hoc_id', $lopHocId)
                            ->where('so_thu_tu', '>', function($query) use ($baiHocId, $lopHocId) {
                                $query->select('so_thu_tu')
                                    ->from('bai_hoc_lops')
                                    ->where('bai_hoc_id', $baiHocId)
                                    ->where('lop_hoc_id', $lopHocId);
                            })
                            ->orderBy('so_thu_tu', 'asc')
                            ->first();
                            
        if ($baiHocTiepTheo) {
            // Tạo tiến độ cho bài học tiếp theo nếu chưa có
            TienDoBaiHoc::firstOrCreate([
                'hoc_vien_id' => $hocVien->id,
                'bai_hoc_id' => $baiHocTiepTheo->bai_hoc_id
            ], [
                'trang_thai' => 'dang_hoc',
                'ngay_bat_dau' => now()
            ]);
            
            return redirect()->route('hoc-vien.bai-hoc.show', ['lopHocId' => $lopHocId, 'baiHocId' => $baiHocTiepTheo->bai_hoc_id])
                    ->with('success', 'Đã hoàn thành bài học. Chuyển đến bài học tiếp theo.');
        }
        
        return redirect()->route('hoc-vien.lop-hoc.show', $lopHocId)
                ->with('success', 'Đã hoàn thành bài học cuối cùng của lớp học.');
    }
    
    /**
     * Hiển thị form nộp bài tập
     */
    public function formNopBaiTap($lopHocId, $baiHocId, $baiTapId)
    {
        // Lấy thông tin người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
      
        
        // Lấy thông tin bài tập
        $baiTap = BaiTap::findOrFail($baiTapId);
        
        // Kiểm tra và đảm bảo dữ liệu hiển thị
        if (empty($baiTap->ten) && !empty($baiTap->tieu_de)) {
            $baiTap->ten = $baiTap->tieu_de;
        }
        
        // Log để debug
        Log::info('Dữ liệu bài tập: ID=' . $baiTap->id . 
            ', Ten=' . ($baiTap->ten ?? 'null') . 
            ', TieuDe=' . ($baiTap->tieu_de ?? 'null') . 
            ', BaiHocID=' . $baiTap->bai_hoc_id);
        
        // Kiểm tra bài tập thuộc bài học đang xem
        if ($baiTap->bai_hoc_id != $baiHocId) {
            return redirect()->route('hoc-vien.bai-hoc.show', ['lopHocId' => $lopHocId, 'baiHocId' => $baiHocId])
                    ->with('error', 'Bài tập không thuộc bài học này');
        }
        
        // Lấy bài tập đã nộp (nếu có)
        $baiTapDaNop = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
                        ->where('bai_tap_id', $baiTapId)
                        ->first();
        
        // Lấy thông tin lớp học
        $lopHoc = LopHoc::findOrFail($lopHocId);
        
        return view('hoc-vien.bai-tap.nop-bai', compact('baiTap', 'baiTapDaNop', 'lopHoc', 'baiHocId', 'hocVien'));
    }
    
    /**
     * Xử lý nộp bài tập
     */
    public function nopBaiTap(Request $request, $lopHocId, $baiHocId, $baiTapId)
    {
        // Lấy thông tin người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
    
        
        // Validate
        $validator = Validator::make($request->all(), [
            'noi_dung' => 'nullable|string',
            'file_dinh_kem' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Kiểm tra bài tập đã nộp chưa
        $baiTapDaNop = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
                        ->where('bai_tap_id', $baiTapId)
                        ->first();
        
        if (!$baiTapDaNop) {
            $baiTapDaNop = new BaiTapDaNop();
            $baiTapDaNop->hoc_vien_id = $hocVien->id;
            $baiTapDaNop->bai_tap_id = $baiTapId;
            $baiTapDaNop->ngay_nop = now();
        } else {
            $baiTapDaNop->ngay_nop = now();
            
            // Xóa file cũ nếu có
            if ($baiTapDaNop->file_path && Storage::disk('public')->exists($baiTapDaNop->file_path)) {
                Storage::disk('public')->delete($baiTapDaNop->file_path);
            }
        }
        
        // Lưu nội dung
        $baiTapDaNop->noi_dung = $request->noi_dung;
        
        // Lưu file đính kèm nếu có
        if ($request->hasFile('file_dinh_kem')) {
            $filePath = $request->file('file_dinh_kem')->store('bai_tap_nop', 'public');
            $baiTapDaNop->file_path = $filePath;
            $baiTapDaNop->ten_file = $request->file('file_dinh_kem')->getClientOriginalName();
        }
        
        $baiTapDaNop->trang_thai = 'da_nop';
        $baiTapDaNop->save();
        
        return redirect()->route('hoc-vien.bai-hoc.show', ['lopHocId' => $lopHocId, 'baiHocId' => $baiHocId])
                ->with('success', 'Nộp bài tập thành công');
    }
    
    /**
     * Tải tài liệu bài học
     */
    public function taiTaiLieu($lopHocId, $baiHocId, $taiLieuId)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Kiểm tra tài liệu tồn tại
        $taiLieu = TaiLieuBoTro::findOrFail($taiLieuId);
        
      
        
        // Kiểm tra file tồn tại
        if (!Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
            return back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa');
        }
        
        return response()->file(storage_path('app/public/' . $taiLieu->duong_dan_file));
    }
    
    /**
     * Xem trực tiếp tài liệu bài học
     */
    public function xemTaiLieu($lopHocId, $baiHocId, $taiLieuId)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Kiểm tra tài liệu tồn tại
        $taiLieu = TaiLieuBoTro::findOrFail($taiLieuId);
        
        // Kiểm tra file tồn tại
        if (!Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
            return back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa');
        }
        
        $filePath = storage_path('app/public/' . $taiLieu->duong_dan_file);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Lấy MIME type dựa trên phần mở rộng của file
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'txt' => 'text/plain',
        ];
        
        $contentType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';
        
        // Trả về file với inline disposition để hiển thị trên trình duyệt thay vì tải xuống
        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $taiLieu->tieu_de . '.' . $fileExtension . '"'
        ]);
    }
} 