<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\BaiHoc;
use App\Models\BaiHocLop;
use App\Models\BaiTap;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NguoiDung;
use App\Models\TaiLieuBoTro;
use App\Models\ThongBaoLopHoc;
use App\Models\TienDoBaiHoc;
use App\Models\BaiTapDaNop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LopHocController extends Controller
{
    /**
     * Hiển thị danh sách lớp học của học viên
     */
    public function index(Request $request)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
        // Lấy tham số tìm kiếm và lọc
        $keyword = $request->input('keyword');
        $trangThai = $request->input('trang_thai');
        
        // Xây dựng query dựa trên đăng ký học
        $dangKyHocs = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->whereIn('trang_thai', ['da_duyet', 'da_xac_nhan'])
            ->pluck('lop_hoc_id')
            ->toArray();
        
        Log::info('Học viên ' . $hocVien->id . ' có ' . count($dangKyHocs) . ' lớp học');
        
        if (empty($dangKyHocs)) {
            Log::warning('Không tìm thấy lớp học nào cho học viên ' . $hocVien->id);
            // Nếu không có lớp học nào, trả về view với danh sách trống
            $lopHocs = collect([]);
            return view('hoc-vien.lop-hoc.index', compact('lopHocs'));
        }
        
        // Xây dựng query lớp học
        $lopHocsQuery = LopHoc::whereIn('id', $dangKyHocs)
            ->with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung']);
        
        // Thêm điều kiện tìm kiếm nếu có
        if (!empty($keyword)) {
            $lopHocsQuery->where(function($query) use ($keyword) {
                $query->where('ten', 'like', '%' . $keyword . '%')
                      ->orWhere('ma_lop', 'like', '%' . $keyword . '%');
            });
        }
        
        // Thêm điều kiện lọc theo trạng thái nếu có
        if (!empty($trangThai)) {
            $lopHocsQuery->where('trang_thai', $trangThai);
        }
        
        // Lấy kết quả query
        $lopHocs = $lopHocsQuery->get();
        
        Log::info('Số lớp học lấy được sau khi lọc: ' . $lopHocs->count());
        
        return view('hoc-vien.lop-hoc.index', compact('lopHocs'));
    }
    
    /**
     * Hiển thị chi tiết lớp học
     */
    public function show($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
        // Kiểm tra học viên có thuộc lớp này không
        $kiemTraDangKy = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $id)
            ->whereIn('trang_thai', ['da_duyet', 'da_xac_nhan'])
            ->exists();
        
        $lopHoc = LopHoc::where('id', $id)
            ->with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung'])
            ->firstOrFail();
        
        // Lấy danh sách bài học của lớp
        $baiHocs = BaiHocLop::where('lop_hoc_id', $id)
            ->with('baiHoc')
            ->orderBy('so_thu_tu')
            ->get();
            
        // Lấy tiến độ học tập của học viên
        $tienDoBaiHocs = TienDoBaiHoc::where('hoc_vien_id', $hocVien->id)
            ->whereIn('bai_hoc_id', $baiHocs->pluck('bai_hoc_id')->toArray())
            ->get()
            ->keyBy('bai_hoc_id');
            
        // Gán tiến độ học tập vào bài học
        foreach ($baiHocs as $baiHoc) {
            if (isset($tienDoBaiHocs[$baiHoc->bai_hoc_id])) {
                $baiHoc->tien_do_hoc_tap = $tienDoBaiHocs[$baiHoc->bai_hoc_id];
            }
        }
        
        // Lấy danh sách bài tập của lớp
        $baiTaps = BaiTap::whereHas('baiHoc.baiHocLops', function($query) use ($id) {
            $query->where('lop_hoc_id', $id);
        })
        ->with(['nopBaiTaps' => function($query) use ($hocVien) {
            $query->where('hoc_vien_id', $hocVien->id);
        }])
        ->get()
        ->map(function($baiTap) use ($hocVien) {
            // Lấy trạng thái bài tập
            $nopBaiTap = $baiTap->nopBaiTaps->first();
                
            if (!$nopBaiTap) {
                if (Carbon::now()->gt(Carbon::parse($baiTap->han_nop))) {
                    $baiTap->trang_thai = 'qua_han';
                } else {
                    $baiTap->trang_thai = 'chua_nop';
                }
            } else {
                $baiTap->trang_thai = $nopBaiTap->trang_thai;
                $baiTap->diem = $nopBaiTap->diem;
            }
            
            return $baiTap;
        });
        
        // Lấy danh sách học viên trong lớp
        $danhSachHocVien = HocVien::whereHas('dangKyHocs', function($query) use ($id) {
            $query->where('lop_hoc_id', $id)
                ->whereIn('trang_thai', ['da_xac_nhan' ]);
        })
        ->with('nguoiDung')
        ->get();
        
        
        return view('hoc-vien.lop-hoc.show', compact(
            'lopHoc',
            'baiHocs',
            'baiTaps',
            'danhSachHocVien',
            'hocVien'
        ));
    }
    
    /**
     * Hiển thị tiến độ học tập
     */
    public function progress($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
     
        
        $lopHoc = LopHoc::where('id', $id)
            ->with(['khoaHoc'])
            ->firstOrFail();
        
        // Lấy tất cả bài học của lớp
        $baiHocs = BaiHocLop::where('lop_hoc_id', $id)
            ->with(['baiHoc', 'tienDos' => function($query) use ($hocVien) {
                $query->where('hoc_vien_id', $hocVien->id);
            }])
            ->orderBy('so_thu_tu')
            ->get()
            ->map(function($baiHoc) use ($hocVien) {
                // Tính số bài tập đã hoàn thành
                $totalBaiTap = BaiTap::where('bai_hoc_id', $baiHoc->bai_hoc_id)->count();
                $completedBaiTap = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
                    ->whereHas('baiTap', function($query) use ($baiHoc) {
                        $query->where('bai_hoc_id', $baiHoc->bai_hoc_id);
                    })
                    ->count();
                
                $baiHoc->total_bai_tap = $totalBaiTap;
                $baiHoc->completed_bai_tap = $completedBaiTap;
                
                // Tính điểm trung bình của bài học
                if ($completedBaiTap > 0) {
                    $avgDiem = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
                        ->whereHas('baiTap', function($query) use ($baiHoc) {
                            $query->where('bai_hoc_id', $baiHoc->bai_hoc_id);
                        })
                        ->whereNotNull('diem')
                        ->avg('diem');
                    
                    $baiHoc->diem_trung_binh = $avgDiem;
                } else {
                    $baiHoc->diem_trung_binh = null;
                }
                
                return $baiHoc;
            });
        
        // Tính số bài học đã hoàn thành
        $completedLessons = $baiHocs->filter(function($baiHoc) {
            return isset($baiHoc->tienDos) && $baiHoc->tienDos->count() > 0 && $baiHoc->tienDos->first()->trang_thai == 'da_hoan_thanh';
        })->count();
        
        // Tính tiến độ hoàn thành
        $totalLessons = $baiHocs->count();
        $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        
        return view('hoc-vien.lop-hoc.progress', compact(
            'lopHoc',
            'baiHocs',
            'completedLessons',
            'totalLessons',
            'progressPercentage',
            'hocVien'
        ));
    }
    
    /**
     * Tìm lớp học theo mã lớp
     */
    public function timLop(Request $request)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
        $maLop = $request->input('ma_lop');
        
        if (!$maLop) {
            return back()->with('error', 'Vui lòng nhập mã lớp');
        }
        
        $lopHoc = LopHoc::where('ma_lop', $maLop)
                ->with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung'])
                ->first();
        
        if (!$lopHoc) {
            return back()->with('error', 'Không tìm thấy lớp học với mã này');
        }
        
        // Kiểm tra xem lớp có đang ở trạng thái nhận học viên không
        if ($lopHoc->trang_thai == 'da_ket_thuc') {
            return back()->with('error', 'Lớp học này đã kết thúc, không thể tham gia');
        }
        
        // Kiểm tra xem học viên đã tham gia lớp này chưa
        $daCoTrongLop = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $lopHoc->id)
            ->exists();
        
        if ($daCoTrongLop) {
            return redirect()->route('hoc-vien.lop-hoc.show', $lopHoc->id)
                    ->with('info', 'Bạn đã là thành viên của lớp học này rồi');
        }
        
        return view('hoc-vien.lop-hoc.join-class', compact('lopHoc', 'hocVien'));
    }
    
    /**
     * Tham gia lớp học
     */
    public function thamGia(Request $request, $id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
        $lopHoc = LopHoc::findOrFail($id);
        
        // Kiểm tra xem học viên đã tham gia lớp này chưa
        if ($lopHoc->hocViens()->where('users.id', $hocVien->id)->exists()) {
            return redirect()->route('hoc-vien.lop-hoc.show', $id)
                ->with('info', 'Bạn đã tham gia lớp học này rồi');
        }
        
        // Kiểm tra lớp học còn nhận học viên không (dựa trên trạng thái)
        if ($lopHoc->trang_thai == 'da_ket_thuc') {
            return back()->with('error', 'Lớp học này đã kết thúc, không thể tham gia');
        }
        
        try {
            DB::beginTransaction();
            
            // Tạo đăng ký học
            $dangKy = new DangKyHoc();
            $dangKy->lop_hoc_id = $id;
            $dangKy->hoc_vien_id = $hocVien->id;
            $dangKy->ngay_dang_ky = now();
            $dangKy->trang_thai = 'da_xac_nhan'; // Đã xác nhận tham gia thông qua mã lớp
            $dangKy->save();
            
            DB::commit();
            
            return redirect()->route('hoc-vien.lop-hoc.show', $id)
                ->with('success', 'Bạn đã tham gia lớp học thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị danh sách lớp học có thể đăng ký
     */
    public function danhSachDangKy(Request $request)
    {
        $user = Auth::user();
        $hocVien = HocVien::where('user_id', $user->id)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách lớp học đang mở đăng ký
        $query = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung'])
                    ->where('trang_thai', 'dang_tuyen_sinh')
                    ->where('ngay_bat_dau', '>', now());
        
        // Lọc theo khóa học nếu có
        if ($request->has('khoa_hoc_id')) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }
        
        $lopHocs = $query->paginate(10);
        
        // Lấy danh sách các khóa học để làm bộ lọc
        $khoaHocs = KhoaHoc::where('trang_thai', 'hoat_dong')->get();
        
        // Kiểm tra lớp nào đã đăng ký
        $daDangKy = DangKyHoc::where('hoc_vien_id', $hocVien->id)
                        ->pluck('lop_hoc_id')
                        ->toArray();
        
        return view('hoc-vien.lop-hoc.dang-ky', compact('lopHocs', 'khoaHocs', 'daDangKy'));
    }
    
    /**
     * Xử lý đăng ký lớp học
     */
    public function dangKyLop($id)
    {
        $user = Auth::user();
        $hocVien = HocVien::where('user_id', $user->id)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Kiểm tra lớp học
        $lopHoc = LopHoc::findOrFail($id);
        
        // Kiểm tra lớp học có đang mở đăng ký không
        if ($lopHoc->trang_thai !== 'dang_tuyen_sinh') {
            return redirect()->route('hoc-vien.lop-hoc.dang-ky')->with('error', 'Lớp học này hiện không mở đăng ký');
        }
        
        // Kiểm tra đã đăng ký chưa
        $kiemTraDaDangKy = DangKyHoc::where('lop_hoc_id', $id)
                                ->where('hoc_vien_id', $hocVien->id)
                                ->exists();
        
        if ($kiemTraDaDangKy) {
            return redirect()->route('hoc-vien.lop-hoc.dang-ky')->with('error', 'Bạn đã đăng ký lớp học này rồi');
        }
        
        // Tạo đăng ký mới
        $dangKy = new DangKyHoc();
        $dangKy->lop_hoc_id = $id;
        $dangKy->hoc_vien_id = $hocVien->id;
        $dangKy->ngay_dang_ky = now();
        $dangKy->trang_thai = 'cho_thanh_toan';
        $dangKy->save();
        
        return redirect()->route('hoc-vien.thanh-toan.create', ['dang_ky_id' => $dangKy->id])
                ->with('success', 'Đăng ký lớp học thành công. Vui lòng hoàn tất thanh toán.');
    }
    
    /**
     * Đánh dấu hoàn thành bài học
     */
    public function completeBaiHoc(Request $request)
    {
        // Lấy ID học viên từ session
        $hocVienId = HocVien::where('nguoi_dung_id', $request->session()->get('nguoi_dung_id'))->first()->id;
        
        // Lấy thông tin bài học
        $baiHocId = $request->bai_hoc_id;
        $baiHoc = BaiHoc::findOrFail($baiHocId);
        
        // Cập nhật tiến độ học tập
        $tienDo = TienDoBaiHoc::updateOrCreate(
            [
                'bai_hoc_id' => $baiHocId,
                'hoc_vien_id' => $hocVienId,
            ],
            [
                'ngay_hoan_thanh' => now(),
                'trang_thai' => 'da_hoan_thanh',
            ]
        );
        
        return redirect()->back()->with('success', 'Đã đánh dấu hoàn thành bài học!');
    }
    
    /**
     * Tải xuống tài liệu
     */
    public function downloadTaiLieu($id)
    {
        $taiLieu = TaiLieuBoTro::findOrFail($id);
        
        return response()->download(public_path('storage/' . $taiLieu->duong_dan_file), $taiLieu->tieu_de);
    }

    /**
     * Hiển thị form tìm kiếm lớp học
     */
    public function formTimKiem()
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy 5 đăng ký học đang chờ xác nhận gần đây nhất
        $dangKyGanDay = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->whereIn('trang_thai', ['cho_xac_nhan', 'cho_thanh_toan'])
            ->with(['lopHoc.khoaHoc', 'lopHoc.giaoVien.nguoiDung'])
            ->orderBy('ngay_dang_ky', 'desc')
            ->limit(5)
            ->get();
        
        // Lấy các lớp học đang diễn ra (có thể gợi ý cho người dùng)
        $lopHocDangDienRa = LopHoc::where('trang_thai', 'dang_dien_ra')
            ->with(['khoaHoc', 'giaoVien.nguoiDung'])
            ->orderBy('tao_luc', 'desc')
            ->limit(5)
            ->get();
            
        // Lấy danh sách các khóa học đang có lớp đang diễn ra để hiển thị gợi ý
        $khoaHocsDangCo = KhoaHoc::whereHas('lopHocs', function($query) {
                $query->where('trang_thai', 'dang_dien_ra');
            })
            ->where('trang_thai', 'hoat_dong')
            ->limit(5)
            ->get();
            
        return view('hoc-vien.lop-hoc.form-tim-kiem', compact('dangKyGanDay', 'lopHocDangDienRa', 'khoaHocsDangCo'));
    }
    
    /**
     * Xử lý tìm kiếm lớp học theo mã lớp
     */
    public function timKiem(Request $request)
    {
        $request->validate([
            'ma_lop' => 'required|string'
        ]);
        
        $maLop = $request->input('ma_lop');
        
        // Lấy người dùng đăng nhập
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Tìm lớp học theo mã - mở rộng thêm tìm kiếm theo tên lớp học nếu không tìm thấy theo mã lớp
        $lopHoc = LopHoc::where('ma_lop', $maLop)
            ->with(['giaoVien.nguoiDung', 'khoaHoc', 'hocViens', 'troGiang.nguoiDung'])
            ->first();
            
        if (!$lopHoc) {
            // Thử tìm theo tên lớp học
            $lopHoc = LopHoc::where('ten', 'like', '%' . $maLop . '%')
                ->with(['giaoVien.nguoiDung', 'khoaHoc', 'hocViens', 'troGiang.nguoiDung'])
                ->first();
                
            if (!$lopHoc) {
                Log::info('Không tìm thấy lớp học với mã hoặc tên: ' . $maLop);
                return redirect()->route('hoc-vien.lop-hoc.form-tim-kiem')
                    ->with('error', 'Không tìm thấy lớp học với mã hoặc tên "' . $maLop . '". Vui lòng kiểm tra lại!');
            }
        }
        
        // Kiểm tra xem học viên đã tham gia lớp này chưa
        $daLaThanhVien = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $lopHoc->id)
            ->whereIn('trang_thai', ['da_xac_nhan'])
            ->exists();
            
        if (!$daLaThanhVien) {
            // Thử kiểm tra qua quan hệ
            $lopHocIds = $hocVien->lopHocs->pluck('id')->toArray();
            $daLaThanhVien = in_array($lopHoc->id, $lopHocIds);
        }
        
        // Kiểm tra xem học viên đã gửi yêu cầu tham gia lớp này chưa
        $daGuiYeuCau = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $lopHoc->id)
            ->whereIn('trang_thai', ['cho_xac_nhan', 'cho_thanh_toan'])
            ->exists();
            
        // Ghi log
        Log::info('Học viên ' . $hocVien->id . ' tìm lớp ' . $lopHoc->id . ' (Mã: ' . $lopHoc->ma_lop . '). Đã là thành viên: ' . ($daLaThanhVien ? 'Có' : 'Không') . '. Đã gửi yêu cầu: ' . ($daGuiYeuCau ? 'Có' : 'Không'));
            
        return view('hoc-vien.lop-hoc.tim-kiem', compact('lopHoc', 'daLaThanhVien', 'daGuiYeuCau'));
    }
    
    /**
     * Xử lý gửi yêu cầu tham gia lớp học
     */
    public function guiYeuCau(Request $request)
    {
        $request->validate([
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'ghi_chu' => 'nullable|string|max:500',
            'dong_y_dieu_khoan' => 'required|accepted'
        ]);
        
        // Lấy người dùng đăng nhập
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        $lopHocId = $request->input('lop_hoc_id');
        $lopHoc = LopHoc::find($lopHocId);
        
        // Kiểm tra học viên đã tham gia lớp học chưa
        $daLaThanhVien = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $lopHocId)
            ->whereIn('trang_thai', ['da_xac_nhan'])
            ->exists();
            
        if ($daLaThanhVien) {
            return redirect()->route('hoc-vien.lop-hoc.show', $lopHoc->id)
                ->with('info', 'Bạn đã là thành viên của lớp học này');
        }
        
        // Kiểm tra học viên đã gửi yêu cầu tham gia lớp này chưa
        $dangKyHienTai = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $lopHocId)
            ->whereIn('trang_thai', ['cho_xac_nhan'])
            ->first();
            
        if ($dangKyHienTai) {
            if ($dangKyHienTai->trang_thai == 'cho_xac_nhan') {
                return redirect()->route('hoc-vien.lop-hoc.yeu-cau')
                    ->with('info', 'Bạn đã gửi yêu cầu tham gia lớp học này và đang chờ phê duyệt');
            } else if ($dangKyHienTai->trang_thai == 'cho_thanh_toan') {
                return redirect()->route('hoc-vien.thanh-toan.create', ['dang_ky_id' => $dangKyHienTai->id])
                    ->with('info', 'Bạn đã đăng ký lớp học này. Vui lòng hoàn tất thanh toán');
            } else {
                return redirect()->route('hoc-vien.lop-hoc.show', $lopHoc->id)
                    ->with('info', 'Yêu cầu tham gia của bạn đã được chấp nhận');
            }
        }
        
        // Tạo đăng ký mới với trạng thái "chờ xác nhận"
        DangKyHoc::create([
            'hoc_vien_id' => $hocVien->id,
            'lop_hoc_id' => $lopHocId,
            'ghi_chu' => $request->input('ghi_chu'),
            'trang_thai' => 'cho_xac_nhan', // Trạng thái chờ xác nhận thay vì cho_thanh_toan
            'ngay_dang_ky' => now()
        ]);
        
        return redirect()->route('hoc-vien.lop-hoc.yeu-cau')
            ->with('success', 'Đã gửi yêu cầu tham gia lớp học thành công');
    }
    
    /**
     * Hiển thị danh sách đăng ký lớp học của học viên
     */
    public function danhSachYeuCau()
    {
        // Lấy người dùng đăng nhập
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')
                ->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách đăng ký học của học viên
        $dangKyHoc = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->with(['lopHoc.khoaHoc', 'lopHoc.giaoVien.nguoiDung'])
            ->orderBy('ngay_dang_ky', 'desc')
            ->paginate(10);
        
        // Log để debug
        Log::info('Học viên ' . $hocVien->id . ' có ' . $dangKyHoc->count() . ' đăng ký học');
        
        // Thông tin thống kê
        $thongKe = [
            'total' => DangKyHoc::where('hoc_vien_id', $hocVien->id)->count(),
            'pending' => DangKyHoc::where('hoc_vien_id', $hocVien->id)
                            ->whereIn('trang_thai', ['cho_xac_nhan', 'cho_thanh_toan'])->count(),
            'approved' => DangKyHoc::where('hoc_vien_id', $hocVien->id)
                            ->whereIn('trang_thai', ['da_duyet', 'da_xac_nhan', 'da_thanh_toan'])->count(),
            'rejected' => DangKyHoc::where('hoc_vien_id', $hocVien->id)
                            ->where('trang_thai', 'tu_choi')->count(),
        ];
            
        return view('hoc-vien.lop-hoc.yeu-cau', compact('dangKyHoc', 'thongKe'));
    }

    /**
     * Hiển thị danh sách học viên trong lớp học
     */
    public function danhSachHocVien($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        if (!$nguoiDungId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để tiếp tục');
        }
        
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại');
        }
        
      
        
        // Lấy thông tin lớp học 
        $lopHoc = LopHoc::with([
            'khoaHoc', 
            'giaoVien.nguoiDung',
            'troGiang.nguoiDung'
        ])
        ->findOrFail($id);
        
        // Lấy tất cả danh sách học viên đã đăng ký và nhóm theo trạng thái
        $dangKyHocs = DangKyHoc::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->get();
            
        // Lọc các học viên đang học thực tế
        $danhSachHocVien = $dangKyHocs->filter(function($dangKy) {
            return in_array($dangKy->trang_thai, ['da_xac_nhan']);
        });
            
        // Đếm số lượng học viên
        $tongSoHocVien = $danhSachHocVien->count();
        
        return view('hoc-vien.lop-hoc.danh-sach-hoc-vien', compact(
            'lopHoc', 
            'danhSachHocVien', 
            'tongSoHocVien',
            'hocVien'
        ));
    }
} 