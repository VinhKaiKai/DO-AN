<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\User;
use App\Models\GiaoVien;
use App\Models\TroGiang;
use App\Models\KhoaHoc;
use App\Models\BaiHoc;
use App\Models\TraLoiBaiTap;
use App\Models\DanhGiaLopHoc;
use App\Models\HoanThanhBaiHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LopHocController extends Controller
{
    /**
     * Hiển thị danh sách lớp học của giáo viên
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Tìm các lớp học của giáo viên
        $lopHocsQuery = \App\Models\LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung', 'baiHocLops', 'dangKyHocs'])
            ->where('giao_vien_id', $giaoVien->id);
            
        // Lọc theo trạng thái nếu có
        if (request()->has('trang_thai') && !empty(request('trang_thai'))) {
            $trangThai = request('trang_thai');
            
            // Cải thiện cách lọc theo trạng thái
            if ($trangThai == 'sap_khai_giang') {
                $lopHocsQuery->where('ngay_bat_dau', '>', now());
            } elseif ($trangThai == 'dang_dien_ra') {
                $lopHocsQuery->where('ngay_bat_dau', '<=', now())
                             ->where('ngay_ket_thuc', '>=', now());
            } elseif ($trangThai == 'da_ket_thuc') {
                $lopHocsQuery->where('ngay_ket_thuc', '<', now());
            }
        }
        
        // Lọc theo khóa học nếu có
        if (request()->has('khoa_hoc_id') && !empty(request('khoa_hoc_id'))) {
            $lopHocsQuery->where('khoa_hoc_id', request('khoa_hoc_id'));
        }
        
        // Lấy danh sách lớp học có phân trang
        $lopHocs = $lopHocsQuery->orderBy('id', 'desc')
            ->paginate(9); // 9 lớp học mỗi trang
            
        // Đếm số học viên mỗi lớp (đã đăng ký và được duyệt)
        foreach ($lopHocs as $lopHoc) {
            // Đếm số học viên có trạng thái hợp lệ
            $lopHoc->soHocVien = $lopHoc->dangKyHocs()
                ->whereIn('trang_thai', ['da_xac_nhan'])
                ->count();
            
            // Đếm số buổi học từ lịch học hoặc bài học lớp
            if (!empty($lopHoc->lich_hoc)) {
                $lichHoc = json_decode($lopHoc->lich_hoc, true);
                if (is_array($lichHoc)) {
                    $lopHoc->so_buoi = count($lichHoc);
                } else {
                    $lopHoc->so_buoi = $lopHoc->baiHocLops->count();
                }
            } else {
                $lopHoc->so_buoi = $lopHoc->baiHocLops->count();
            }
            
            // Đảm bảo có giá trị mặc định cho số học viên tối đa
            if (empty($lopHoc->so_luong_toi_da)) {
                $lopHoc->so_luong_toi_da = 30; // Giá trị mặc định
            }
            
            // Cập nhật trạng thái hiển thị
            if ($lopHoc->ngay_bat_dau > now()) {
                $lopHoc->trang_thai_hien_thi = 'sap_khai_giang';
            } elseif ($lopHoc->ngay_ket_thuc > now()) {
                $lopHoc->trang_thai_hien_thi = 'dang_dien_ra';
            } else {
                $lopHoc->trang_thai_hien_thi = 'da_ket_thuc';
            }
        }
        
        // Lấy danh sách tất cả khóa học để hiển thị trong dropdown lọc
        $khoaHocs = \App\Models\KhoaHoc::orderBy('ten', 'asc')->get();
        
        // Lấy các tham số lọc hiện tại
        $currentFilters = request()->all();
        
        return view('giao-vien.lop-hoc.index', compact('lopHocs', 'khoaHocs', 'currentFilters'));
    }
    
    /**
     * Hiển thị form thêm học viên vào lớp học
     */
    public function addStudentForm($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Kiểm tra quyền truy cập
        $lopHoc = LopHoc::where('id', $id)
                ->where('giao_vien_id', $giaoVien->id)
                ->with(['khoaHoc', 'giaoVien.nguoiDung'])
                ->firstOrFail();
        
        // Lấy danh sách học viên đã tham gia lớp học
        $dangKyHocs = \App\Models\DangKyHoc::where('lop_hoc_id', $id)
                    ->where('trang_thai', 'da_xac_nhan')
                    ->with(['hocVien.nguoiDung'])
                    ->get();
        
        $hocViens = collect();
        
        foreach ($dangKyHocs as $dangKy) {
            if ($dangKy->hocVien && $dangKy->hocVien->nguoiDung) {
                $hocVien = $dangKy->hocVien;
                $hocVien->ho_ten = $dangKy->hocVien->nguoiDung->ho_ten ?? $dangKy->hocVien->nguoiDung->ho . ' ' . $dangKy->hocVien->nguoiDung->ten;
                $hocVien->email = $dangKy->hocVien->nguoiDung->email;
                $hocVien->dien_thoai = $dangKy->hocVien->nguoiDung->dien_thoai;
                $hocVien->pivot = (object) ['tao_luc' => $dangKy->ngay_dang_ky];
                $hocViens->push($hocVien);
            }
        }
        
        $lopHoc->hocViens = $hocViens;
        
        return view('giao-vien.lop-hoc.add-student', compact('lopHoc'));
    }
    
    /**
     * Thêm học viên vào lớp học bằng mã học viên
     */
    public function addStudent(Request $request, $id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Kiểm tra quyền truy cập
        $lopHoc = LopHoc::where('id', $id)
                ->where('giao_vien_id', $giaoVien->id)
                ->firstOrFail();
        
        $request->validate([
            'ma_hoc_vien' => 'required|string|exists:hoc_viens,ma_hoc_vien',
        ], [
            'ma_hoc_vien.required' => 'Vui lòng nhập mã học viên',
            'ma_hoc_vien.exists' => 'Mã học viên không tồn tại trong hệ thống',
        ]);
        
        // Tìm học viên theo mã
        $hocVien = HocVien::where('ma_hoc_vien', $request->ma_hoc_vien)->first();
        
        // Kiểm tra học viên đã trong lớp chưa
        $exists = DangKyHoc::where('lop_hoc_id', $id)
                    ->where('hoc_vien_id', $hocVien->id)
                    ->exists();
        
        if ($exists) {
            return back()->with('error', 'Học viên này đã trong lớp học!');
        }
        
        try {
            DB::beginTransaction();
            
            // Thêm học viên vào lớp học
            $dangKy = new DangKyHoc();
            $dangKy->lop_hoc_id = $id;
            $dangKy->hoc_vien_id = $hocVien->id;
            $dangKy->ngay_dang_ky = now();
            $dangKy->trang_thai = 'da_xac_nhan';
            $dangKy->save();
            
            DB::commit();
            
            return redirect()->route('giao-vien.lop-hoc.add-student-form', $id)
                    ->with('success', 'Đã thêm học viên vào lớp học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Thêm học viên vào lớp học bằng email
     */
    public function addStudentByEmail(Request $request, $id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Kiểm tra quyền truy cập
        $lopHoc = LopHoc::where('id', $id)
                ->where('giao_vien_id', $giaoVien->id)
                ->firstOrFail();
        
        $request->validate([
            'email' => 'required|email|exists:nguoi_dungs,email',
        ], [
            'email.required' => 'Vui lòng nhập email học viên',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);
        
        // Tìm user và học viên theo email
        $nguoiDung = \App\Models\NguoiDung::where('email', $request->email)->first();
        $hocVien = \App\Models\HocVien::where('nguoi_dung_id', $nguoiDung->id)->first();
        
        if (!$hocVien) {
            return back()->with('error', 'Người dùng này không phải là học viên!');
        }
        
        // Kiểm tra học viên đã trong lớp chưa
        $exists = DangKyHoc::where('lop_hoc_id', $id)
                    ->where('hoc_vien_id', $hocVien->id)
                    ->exists();
        
        if ($exists) {
            return back()->with('error', 'Học viên này đã trong lớp học!');
        }
        
        try {
            DB::beginTransaction();
            
            // Thêm học viên vào lớp học
            $dangKy = new DangKyHoc();
            $dangKy->lop_hoc_id = $id;
            $dangKy->hoc_vien_id = $hocVien->id;
            $dangKy->ngay_dang_ky = now();
            $dangKy->trang_thai = 'da_xac_nhan';
            $dangKy->save();
            
            DB::commit();
            
            return redirect()->route('giao-vien.lop-hoc.add-student-form', $id)
                    ->with('success', 'Đã thêm học viên vào lớp học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa học viên khỏi lớp học
     */
    public function removeStudent($id, $hocVienId)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Kiểm tra quyền truy cập
        $lopHoc = LopHoc::where('id', $id)
                ->where('giao_vien_id', $giaoVien->id)
                ->firstOrFail();
        
        // Tìm đăng ký học để xóa
        $dangKy = DangKyHoc::where('lop_hoc_id', $id)
                    ->where('hoc_vien_id', $hocVienId)
                    ->first();
        
        if (!$dangKy) {
            return back()->with('error', 'Không tìm thấy học viên trong lớp học!');
        }
        
        try {
            DB::beginTransaction();
            
            // Xóa đăng ký học
            $dangKy->delete();
            
            DB::commit();
            
            return redirect()->route('giao-vien.lop-hoc.add-student-form', $id)
                    ->with('success', 'Đã xóa học viên khỏi lớp học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị danh sách học viên của lớp học
     */
    public function danhSachHocVien($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Lấy thông tin lớp học và kiểm tra quyền truy cập
        $lopHoc = \App\Models\LopHoc::with([
            'khoaHoc', 
            'giaoVien.nguoiDung',
            'troGiang.nguoiDung'
        ])
        ->where('giao_vien_id', $giaoVien->id)
        ->findOrFail($id);
        
        // Lấy danh sách học viên đã đăng ký
        $dangKyHocs = \App\Models\DangKyHoc::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->get()
            ->groupBy('trang_thai');
            
        // Đếm số lượng theo trạng thái
        $tongSo = $dangKyHocs->flatten()->count();
        $daXacNhan = isset($dangKyHocs['da_xac_nhan']) ? $dangKyHocs['da_xac_nhan']->count() : 0;
        $dangHoc = isset($dangKyHocs['dang_hoc']) ? $dangKyHocs['dang_hoc']->count() : 0;
        $chuaXacNhan = isset($dangKyHocs['cho_xac_nhan']) ? $dangKyHocs['cho_xac_nhan']->count() : 0;
        $daHuy = isset($dangKyHocs['da_huy']) ? $dangKyHocs['da_huy']->count() : 0;
        
        return view('giao-vien.lop-hoc.danh-sach-hoc-vien', compact(
            'lopHoc', 
            'dangKyHocs', 
            'tongSo', 
            'daXacNhan',
            'dangHoc', 
            'chuaXacNhan', 
            'daHuy'
        ));
    }
    
    
    /**
     * Hiển thị lịch giảng dạy của giáo viên
     */
    public function lichDay()
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Lấy danh sách lớp học đang dạy
        $lopHocs = \App\Models\LopHoc::with(['khoaHoc'])
            ->where('giao_vien_id', $giaoVien->id)
            ->where('trang_thai', 'dang_dien_ra')
            ->get();
            
        // Tính số tiết dạy trong tuần và số giờ dạy trong tuần
        $soTietDayTrongTuan = 0;
        $soGioDayTrongTuan = 0;
        $tongSoHocVien = 0;
        
        foreach ($lopHocs as $lopHoc) {
            // Tính số học viên
            $tongSoHocVien += $lopHoc->dangKyHocs()->whereIn('trang_thai', ['da_duyet', 'dang_hoc'])->count();
            
            // Tính số tiết dạy trong tuần
            $lichHoc = json_decode($lopHoc->lich_hoc, true);
            if (is_array($lichHoc)) {
                $soTietDayTrongTuan += count($lichHoc);
                
                // Tính số giờ dạy
                foreach ($lichHoc as $lich) {
                    if (isset($lich['gio'])) {
                        $gioHoc = explode(' - ', $lich['gio']);
                        if (count($gioHoc) == 2) {
                            $batDau = \Carbon\Carbon::createFromFormat('H:i', $gioHoc[0]);
                            $ketThuc = \Carbon\Carbon::createFromFormat('H:i', $gioHoc[1]);
                            $soGioDayTrongTuan += $batDau->diffInHours($ketThuc);
                        }
                    }
                }
            }
        }
            
        return view('giao-vien.lop-hoc.lich-day', compact('lopHocs', 'soTietDayTrongTuan', 'soGioDayTrongTuan', 'tongSoHocVien'));
    }
    
    

    /**
     * Lưu lớp học mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Giáo viên không có quyền tạo lớp học mới
        return abort(403, 'Bạn không có quyền thực hiện hành động này');
    }

    /**
     * Hiển thị thông tin chi tiết lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('giao-vien.dashboard')
                ->with('error', 'Không tìm thấy thông tin nhân viên');
        }
        
        // Lấy thông tin lớp học và kiểm tra quyền truy cập
        $lopHoc = \App\Models\LopHoc::with([
            'khoaHoc', 
            'giaoVien.nguoiDung',
            'troGiang.nguoiDung'
        ])
        ->where('giao_vien_id', $giaoVien->id)
        ->findOrFail($id);
        
        // Lấy danh sách học viên đã đăng ký và được duyệt
        $dangKyHocs = \App\Models\DangKyHoc::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->whereIn('trang_thai', ['da_duyet', 'dang_hoc'])
            ->get();
        
        // Lấy danh sách yêu cầu tham gia đang chờ duyệt
        $yeuCauThamGias = \App\Models\YeuCauThamGia::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->get();
        
        // Lấy danh sách bài học của lớp học
        $baiHocLops = \App\Models\BaiHocLop::where('lop_hoc_id', $id)
            ->orderBy('so_thu_tu', 'asc')
            ->get();
            
        // Lấy ID của các bài học
        $baiHocIds = $baiHocLops->pluck('bai_hoc_id')->toArray();
        
        // Lấy thông tin chi tiết của các bài học
        $baiHocsData = \App\Models\BaiHoc::whereIn('id', $baiHocIds)->get()->keyBy('id');
        
        // Chuẩn bị dữ liệu bài học để truyền vào view
        $baiHocs = collect();
        
        foreach ($baiHocLops as $baiHocLop) {
            if (isset($baiHocsData[$baiHocLop->bai_hoc_id])) {
                $baiHoc = $baiHocsData[$baiHocLop->bai_hoc_id];
                
                // Thêm các thuộc tính từ bảng trung gian vào đối tượng bài học
                $baiHoc->thu_tu = $baiHocLop->so_thu_tu;
                $baiHoc->ngay_bat_dau = $baiHocLop->ngay_bat_dau;
                $baiHoc->ngay_day = $baiHocLop->ngay_bat_dau; // Để tương thích với view
                $baiHoc->trang_thai = $baiHocLop->trang_thai ?? 'chua_bat_dau';
                $baiHoc->da_hoan_thanh = ($baiHocLop->trang_thai ?? '') === 'da_hoan_thanh';
                $baiHoc->ten = $baiHoc->tieu_de; // Map tên trường cho phù hợp với view
                $baiHoc->thoi_luong = $baiHoc->thoi_luong ?? 0;
                
                $baiHocs->push($baiHoc);
            }
        }
        
        return view('giao-vien.lop-hoc.show', compact('lopHoc', 'dangKyHocs', 'baiHocs', 'yeuCauThamGias'));
    }

    /**
     * Hiển thị form chỉnh sửa lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Giáo viên không có quyền sửa thông tin lớp học
        return abort(403, 'Bạn không có quyền thực hiện hành động này');
    }

    /**
     * Cập nhật thông tin lớp học
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Giáo viên không có quyền sửa thông tin lớp học
        return abort(403, 'Bạn không có quyền thực hiện hành động này');
    }

    /**
     * Xóa lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Giáo viên không có quyền xóa lớp học
        return abort(403, 'Bạn không có quyền thực hiện hành động này');
    }
    
    /**
     * Hiển thị kết quả học tập của lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ketQua($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Lấy thông tin lớp học và kiểm tra quyền truy cập
        $lopHoc = \App\Models\LopHoc::with([
            'khoaHoc', 
            'giaoVien.nguoiDung',
            'troGiang.nguoiDung'
        ])
        ->where('giao_vien_id', $giaoVien->id)
        ->findOrFail($id);
        
        // Lấy danh sách học viên đã đăng ký và được duyệt
        $dangKyHocs = \App\Models\DangKyHoc::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->whereIn('trang_thai', ['da_duyet', 'dang_hoc'])
            ->get();
            
        // Lấy danh sách bài tập và kết quả
        $ketQuaHocTaps = collect();
        
        foreach ($dangKyHocs as $dangKy) {
            $hocVien = $dangKy->hocVien;
            
            // Lấy tất cả bài tập đã nộp của học viên
            $baiTapDaNops = \App\Models\BaiTapDaNop::with(['baiTap'])
                ->whereHas('baiTap.baiHoc.baiHocLops', function ($query) use ($id) {
                    $query->where('lop_hoc_id', $id);
                })
                ->where('hoc_vien_id', $hocVien->id)
                ->get();
                
            // Tính điểm trung bình và số bài đã nộp
            $tongDiem = 0;
            $soBaiDaCham = 0;
            
            foreach ($baiTapDaNops as $baiTapDaNop) {
                if ($baiTapDaNop->diem !== null) {
                    $tongDiem += $baiTapDaNop->diem;
                    $soBaiDaCham++;
                }
            }
            
            $diemTrungBinh = $soBaiDaCham > 0 ? round($tongDiem / $soBaiDaCham, 1) : null;
            
            // Thêm vào collection kết quả
            $ketQuaHocTaps->push([
                'hoc_vien' => $hocVien,
                'nguoi_dung' => $hocVien->nguoiDung,
                'so_bai_da_nop' => $baiTapDaNops->count(),
                'so_bai_da_cham' => $soBaiDaCham,
                'diem_trung_binh' => $diemTrungBinh,
                'tien_do' => $this->tinhTienDoHocTap($hocVien->id, $id),
            ]);
        }
        
        return view('giao-vien.lop-hoc.ket-qua', compact('lopHoc', 'ketQuaHocTaps'));
    }
    
    /**
     * Tính tiến độ học tập của học viên trong lớp
     *
     * @param  int  $hocVienId
     * @param  int  $lopHocId
     * @return float
     */
    private function tinhTienDoHocTap($hocVienId, $lopHocId)
    {
        // Lấy số bài học trong lớp
        $tongSoBaiHoc = \App\Models\BaiHocLop::where('lop_hoc_id', $lopHocId)->count();
        
        if ($tongSoBaiHoc == 0) {
            return 0;
        }
        
        // Lấy số bài học đã hoàn thành
        $soBaiHocHoanThanh = \App\Models\TienDoBaiHoc::whereHas('baiHoc.baiHocLops', function ($query) use ($lopHocId) {
                $query->where('lop_hoc_id', $lopHocId);
            })
            ->where('hoc_vien_id', $hocVienId)
            ->where('da_hoan_thanh', true)
            ->count();
            
        // Tính phần trăm tiến độ
        return round(($soBaiHocHoanThanh / $tongSoBaiHoc) * 100, 1);
    }

    /**
     * Xác nhận học viên vào lớp
     *
     * @param int $id ID của lớp học
     * @param int $dangKyId ID của đăng ký học
     * @return \Illuminate\Http\Response
     */
    public function xacNhanHocVien($id, $dangKyId, Request $request)
    {
        try {
            // Lấy ID người dùng từ session
            $nguoiDungId = session('nguoi_dung_id');
            $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
            
            if (!$giaoVien) {
                return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên. Vui lòng đăng nhập lại.');
            }
            
            // Kiểm tra quyền truy cập
            $lopHoc = LopHoc::where('id', $id)
                    ->where('giao_vien_id', $giaoVien->id)
                    ->firstOrFail();
            
            // Lấy thông tin đăng ký học
            $dangKy = DangKyHoc::with('hocVien.nguoiDung')->findOrFail($dangKyId);
            
            // Kiểm tra trạng thái đăng ký
            if ($dangKy->trang_thai !== 'cho_xac_nhan') {
                return redirect()->back()
                    ->with('error', 'Yêu cầu này đã được xử lý trước đó');
            }
            
            // Kiểm tra sĩ số lớp học
            $currentStudents = $lopHoc->dangKyHocs()->whereIn('trang_thai', ['da_xac_nhan', 'dang_hoc'])->count();
         
            
            DB::beginTransaction();
            try {
                // Cập nhật trạng thái đăng ký học
                $dangKy->trang_thai = 'da_xac_nhan';
                $dangKy->save();
                
                DB::commit();
                
                return redirect()->route('giao-vien.lop-hoc.danh-sach-hoc-vien', $id)
                        ->with('success', 'Đã xác nhận học viên ' . $dangKy->hocVien->nguoiDung->ho_ten . ' thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xác nhận học viên: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Từ chối học viên vào lớp học
     *
     * @param Request $request
     * @param int $id ID của lớp học
     * @param int $dangKyId ID của đăng ký học
     * @return \Illuminate\Http\Response
     */
    public function tuChoiHocVien($id, $dangKyId, Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ], [
            'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối',
            'ly_do_tu_choi.max' => 'Lý do từ chối không được vượt quá 500 ký tự',
        ]);
        
        try {
            // Lấy ID người dùng từ session
            $nguoiDungId = session('nguoi_dung_id');
            $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
            
            if (!$giaoVien) {
                return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên. Vui lòng đăng nhập lại.');
            }
            
            // Kiểm tra quyền truy cập
            $lopHoc = LopHoc::where('id', $id)
                    ->where('giao_vien_id', $giaoVien->id)
                    ->firstOrFail();
            
            // Lấy thông tin đăng ký học
            $dangKy = DangKyHoc::with('hocVien.nguoiDung')->findOrFail($dangKyId);
            
            // Kiểm tra trạng thái đăng ký
            if ($dangKy->trang_thai !== 'cho_xac_nhan') {
                return redirect()->back()
                    ->with('error', 'Yêu cầu này đã được xử lý trước đó');
            }
            
            DB::beginTransaction();
            try {
                // Cập nhật trạng thái đăng ký học
                $dangKy->trang_thai = 'tu_choi';
                $dangKy->ly_do_tu_choi = $request->ly_do_tu_choi;
                $dangKy->save();
                
                // Tạo thông báo cho học viên
                if (class_exists('\App\Models\ThongBao')) {
                    $thongBao = new \App\Models\ThongBao();
                    $thongBao->nguoi_dung_id = $dangKy->hocVien->nguoi_dung_id;
                    $thongBao->tieu_de = 'Đăng ký lớp học đã bị từ chối';
                    $thongBao->noi_dung = "Đăng ký tham gia lớp {$lopHoc->ten} ({$lopHoc->ma_lop}) của bạn đã bị từ chối. Lý do: {$request->ly_do_tu_choi}";
                    $thongBao->loai = 'thong_bao_tu_choi_lop';
                    $thongBao->da_doc = false;
                    $thongBao->save();
                }
                
                DB::commit();
                
                return redirect()->route('giao-vien.lop-hoc.danh-sach-hoc-vien', $id)
                        ->with('success', 'Đã từ chối học viên ' . $dangKy->hocVien->nguoiDung->ho_ten . ' thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Lỗi từ chối học viên: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách yêu cầu tham gia lớp học
     */
    public function danhSachYeuCauThamGia($id, Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên. Vui lòng đăng nhập lại.');
        }
        
        // Lấy thông tin lớp học và kiểm tra quyền truy cập
        $lopHoc = LopHoc::with([
            'khoaHoc', 
            'giaoVien.nguoiDung'
        ])
        ->where('giao_vien_id', $giaoVien->id)
        ->findOrFail($id);
        
        // Lấy danh sách yêu cầu tham gia lớp
        $yeuCauThamGias = \App\Models\YeuCauThamGia::with(['hocVien.nguoiDung'])
            ->where('lop_hoc_id', $id)
            ->orderBy('tao_luc', 'desc')
            ->get()
            ->groupBy('trang_thai');
        
        // Đếm số lượng theo trạng thái
        $tongSo = isset($yeuCauThamGias) ? $yeuCauThamGias->flatten()->count() : 0;
        $choDuyet = isset($yeuCauThamGias['cho_xac_nhan']) ? $yeuCauThamGias['cho_xac_nhan']->count() : 0;
        $daDuyet = isset($yeuCauThamGias['da_duyet']) ? $yeuCauThamGias['da_duyet']->count() : 0;
        $daHuy = isset($yeuCauThamGias['da_huy']) ? $yeuCauThamGias['da_huy']->count() : 0;
        
        return view('giao-vien.lop-hoc.danh-sach-yeu-cau', compact(
            'lopHoc', 
            'yeuCauThamGias', 
            'tongSo', 
            'choDuyet',
            'daDuyet', 
            'daHuy'
        ));
    }
    
    /**
     * Xử lý yêu cầu tham gia lớp học
     */
    public function xuLyYeuCauThamGia($id, $yeuCauId, Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên. Vui lòng đăng nhập lại.');
        }
        
        // Kiểm tra quyền truy cập
        $lopHoc = LopHoc::where('id', $id)
                ->where('giao_vien_id', $giaoVien->id)
                ->firstOrFail();
        
        // Lấy thông tin yêu cầu tham gia
        $yeuCau = \App\Models\YeuCauThamGia::with(['hocVien.nguoiDung'])
                ->where('lop_hoc_id', $id)
                ->findOrFail($yeuCauId);
        
        // Kiểm tra nếu yêu cầu đã được xử lý
        if ($yeuCau->trang_thai !== 'cho_xac_nhan') {
            return back()->with('error', 'Yêu cầu này đã được xử lý trước đó.');
        }
        
        // Validate action
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'ly_do' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            if ($validated['action'] === 'approve') {
                // Chấp nhận yêu cầu
                $yeuCau->trang_thai = 'da_duyet';
                $yeuCau->xu_ly_luc = now();
                $yeuCau->save();
                
                // Tạo bản ghi đăng ký học
                $dangKy = new DangKyHoc();
                $dangKy->lop_hoc_id = $id;
                $dangKy->hoc_vien_id = $yeuCau->hoc_vien_id;
                $dangKy->ngay_dang_ky = now();
                $dangKy->trang_thai = 'da_xac_nhan';
                $dangKy->save();
                
                // Tạo thông báo cho học viên
                if (class_exists('\App\Models\ThongBao')) {
                    $thongBao = new \App\Models\ThongBao();
                    $thongBao->nguoi_dung_id = $yeuCau->hocVien->nguoi_dung_id;
                    $thongBao->tieu_de = 'Yêu cầu tham gia lớp học đã được chấp nhận';
                    $thongBao->noi_dung = "Yêu cầu tham gia lớp học {$lopHoc->ten} của bạn đã được chấp nhận.";
                    $thongBao->loai = 'thong_bao_duyet_lop';
                    $thongBao->da_doc = false;
                    $thongBao->save();
                }
                
                $message = 'Đã chấp nhận yêu cầu tham gia lớp học thành công.';
            } else {
                // Từ chối yêu cầu
                $yeuCau->trang_thai = 'da_huy';
                $yeuCau->ly_do_tu_choi = $validated['ly_do'] ?? 'Yêu cầu không được chấp nhận';
                $yeuCau->xu_ly_luc = now();
                $yeuCau->save();
                
                // Tạo thông báo cho học viên
                if (class_exists('\App\Models\ThongBao')) {
                    $thongBao = new \App\Models\ThongBao();
                    $thongBao->nguoi_dung_id = $yeuCau->hocVien->nguoi_dung_id;
                    $thongBao->tieu_de = 'Yêu cầu tham gia lớp học đã bị từ chối';
                    $thongBao->noi_dung = "Yêu cầu tham gia lớp học {$lopHoc->ten} của bạn đã bị từ chối. Lý do: " . ($validated['ly_do'] ?? 'Không đáp ứng đủ điều kiện');
                    $thongBao->loai = 'thong_bao_tu_choi_lop';
                    $thongBao->da_doc = false;
                    $thongBao->save();
                }
                
                $message = 'Đã từ chối yêu cầu tham gia lớp học.';
            }
            
            DB::commit();
            
            return redirect()->route('giao-vien.lop-hoc.danh-sach-yeu-cau', $id)
                        ->with('success', $message);
                        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xử lý yêu cầu tham gia: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 