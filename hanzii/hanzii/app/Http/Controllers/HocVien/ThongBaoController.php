<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\ThongBaoLopHoc;
use App\Models\HocVien;
use App\Models\DangKyHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongBaoController extends Controller
{
    /**
     * Hiển thị danh sách thông báo
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách lớp học mà học viên đang tham gia
        $lopHocIds = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_xac_nhan')
            ->pluck('lop_hoc_id')
            ->toArray();
            
        // Nếu có chọn lọc theo lớp học cụ thể
        $lopHocId = $request->input('lop_hoc_id');
        if ($lopHocId && in_array($lopHocId, $lopHocIds)) {
            $selectedLopHocIds = [$lopHocId];
        } else {
            $selectedLopHocIds = $lopHocIds;
        }
        
        // Lấy danh sách thông báo
        $query = ThongBaoLopHoc::with(['lopHoc', 'nguoiTao'])
            ->whereIn('lop_hoc_id', $selectedLopHocIds)
            ->where('trang_thai', ThongBaoLopHoc::TRANG_THAI_KICH_HOAT)
            ->where(function ($q) {
                $q->whereNull('ngay_hieu_luc')
                    ->orWhere('ngay_hieu_luc', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ngay_het_han')
                    ->orWhere('ngay_het_han', '>=', now());
            });
        
        // Tìm kiếm theo tiêu đề hoặc nội dung
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('noi_dung', 'like', "%{$search}%");
            });
        }
        
        // Lấy danh sách thông báo đã đọc của học viên
        $daDocIds = DB::table('thong_bao_da_docs')
            ->where('hoc_vien_id', $hocVien->id)
            ->where('da_doc', 1)
            ->pluck('thong_bao_id')
            ->toArray();
        
        // Lọc theo trạng thái đã đọc/chưa đọc
        if ($request->has('da_doc') && $request->da_doc != '') {
            if ($request->da_doc == 1) {
                $query->whereIn('id', $daDocIds);
            } else {
                $query->whereNotIn('id', $daDocIds);
            }
        }
        
        $thongBaos = $query->orderBy('tao_luc', 'desc')->paginate(10);
        
        // Đánh dấu thông báo là "đã xem" trong danh sách
        foreach ($thongBaos as $thongBao) {
            $thongBao->da_doc = in_array($thongBao->id, $daDocIds);
        }
        
        // Lấy danh sách lớp học để hiển thị filter
        $lopHocs = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_xac_nhan')
            ->with('lopHoc')
            ->get()
            ->pluck('lopHoc');
        
        return view('hoc-vien.thong-bao.index', compact('thongBaos', 'lopHocs', 'lopHocId'));
    }
    
    /**
     * Hiển thị chi tiết thông báo
     */
    public function show($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.d')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách lớp học mà học viên đang tham gia
        $lopHocIds = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_xac_nhan')
            ->pluck('lop_hoc_id')
            ->toArray();
        
        // Lấy thông tin thông báo
        $thongBao = ThongBaoLopHoc::with(['lopHoc', 'lopHoc.khoaHoc', 'nguoiTao'])
            ->where('trang_thai', ThongBaoLopHoc::TRANG_THAI_KICH_HOAT)
            ->findOrFail($id);
        
        // Kiểm tra xem học viên có quyền xem thông báo này không
        if (!in_array($thongBao->lop_hoc_id, $lopHocIds)) {
            return redirect()->route('hoc-vien.thong-bao.index')
                ->with('error', 'Bạn không có quyền xem thông báo này');
        }
        
        // Kiểm tra xem thông báo có hiệu lực không
        if (!$thongBao->daCoHieuLuc() || $thongBao->daHetHan()) {
            return redirect()->route('hoc-vien.thong-bao.index')
                ->with('error', 'Thông báo này không còn hiệu lực');
        }
        
        // Đánh dấu thông báo là đã đọc
        DB::table('thong_bao_da_docs')->updateOrInsert(
            [
                'thong_bao_id' => $thongBao->id,
                'hoc_vien_id' => $hocVien->id,
            ],
            [
                'da_doc' => 1,
                'ngay_doc' => now(),
                'tao_luc' => now(),
                'updated_at' => now(),
            ]
        );
        
        return view('hoc-vien.thong-bao.show', compact('thongBao'));
    }
    
    /**
     * Đánh dấu tất cả thông báo của lớp học là đã đọc
     */
    public function markAllAsRead(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.d')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách lớp học mà học viên đang tham gia
        $lopHocIds = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_xac_nhan')
            ->pluck('lop_hoc_id')
            ->toArray();
        
        // Nếu có chọn lọc theo lớp học cụ thể
        $lopHocId = $request->input('lop_hoc_id');
        if ($lopHocId && in_array($lopHocId, $lopHocIds)) {
            $selectedLopHocIds = [$lopHocId];
        } else {
            $selectedLopHocIds = $lopHocIds;
        }
        
        // Lấy danh sách thông báo cần đánh dấu đã đọc
        $thongBaoIds = ThongBaoLopHoc::whereIn('lop_hoc_id', $selectedLopHocIds)
            ->where('trang_thai', ThongBaoLopHoc::TRANG_THAI_KICH_HOAT)
            ->where(function ($q) {
                $q->whereNull('ngay_hieu_luc')
                    ->orWhere('ngay_hieu_luc', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ngay_het_han')
                    ->orWhere('ngay_het_han', '>=', now());
            })
            ->pluck('id');
        
        // Đánh dấu tất cả thông báo là đã đọc
        foreach ($thongBaoIds as $thongBaoId) {
            DB::table('thong_bao_da_docs')->updateOrInsert(
                [
                    'thong_bao_id' => $thongBaoId,
                    'hoc_vien_id' => $hocVien->id,
                ],
                [
                    'da_doc' => 1,
                    'ngay_doc' => now(),
                    'tao_luc' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        return redirect()->back()
            ->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }
    
    /**
     * Đếm số thông báo chưa đọc
     */
    public function countUnread()
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return response()->json(['unread_count' => 0]);
        }
        
        // Lấy danh sách lớp học mà học viên đang tham gia
        $lopHocIds = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_xac_nhan')
            ->pluck('lop_hoc_id')
            ->toArray();
        
        // Lấy danh sách thông báo đã đọc của học viên
        $daDocIds = DB::table('thong_bao_da_docs')
            ->where('hoc_vien_id', $hocVien->id)
            ->where('da_doc', 1)
            ->pluck('thong_bao_id')
            ->toArray();
        
        // Đếm số thông báo chưa đọc
        $unreadCount = ThongBaoLopHoc::whereIn('lop_hoc_id', $lopHocIds)
            ->where('trang_thai', ThongBaoLopHoc::TRANG_THAI_KICH_HOAT)
            ->where(function ($q) {
                $q->whereNull('ngay_hieu_luc')
                    ->orWhere('ngay_hieu_luc', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ngay_het_han')
                    ->orWhere('ngay_het_han', '>=', now());
            })
            ->whereNotIn('id', $daDocIds)
            ->count();
        
        return response()->json(['unread_count' => $unreadCount]);
    }
} 