<?php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách thông báo theo người dùng hiện tại
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        if (!$nguoiDungId) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập',
            ], 401);
        }
        
        // Lấy danh sách thông báo
        $query = ThongBao::where('nguoi_dung_id', $nguoiDungId)
                         ->orderBy('created_at', 'desc');
                         
        // Lọc theo đã đọc / chưa đọc nếu có
        if ($request->has('da_doc')) {
            $query->where('da_doc', $request->da_doc == 1);
        }
        
        // Lọc theo loại thông báo nếu có
        if ($request->has('loai') && !empty($request->loai)) {
            $query->where('loai', $request->loai);
        }
        
        // Phân trang
        $perPage = $request->input('per_page', 10);
        $thongBaos = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $thongBaos,
        ]);
    }
    
    /**
     * Đánh dấu thông báo đã được đọc
     *
     * @param Request $request
     * @param int $id ID của thông báo
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        if (!$nguoiDungId) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập',
            ], 401);
        }
        
        $thongBao = ThongBao::where('id', $id)
                          ->where('nguoi_dung_id', $nguoiDungId)
                          ->first();
        
        if (!$thongBao) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo',
            ], 404);
        }
        
        // Cập nhật trạng thái đã đọc
        $thongBao->da_doc = true;
        $thongBao->ngay_doc = Carbon::now();
        $thongBao->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu thông báo là đã đọc',
            'data' => $thongBao,
        ]);
    }
    
    /**
     * Đánh dấu tất cả thông báo của người dùng là đã đọc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        if (!$nguoiDungId) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập',
            ], 401);
        }
        
        // Cập nhật tất cả thông báo chưa đọc
        ThongBao::where('nguoi_dung_id', $nguoiDungId)
               ->where('da_doc', false)
               ->update([
                   'da_doc' => true,
                   'ngay_doc' => Carbon::now(),
               ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu tất cả thông báo là đã đọc',
        ]);
    }
    
    /**
     * Đếm số thông báo chưa đọc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function countUnread(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        if (!$nguoiDungId) {
            return response()->json([
                'unread_count' => 0,
            ]);
        }
        
        $count = ThongBao::where('nguoi_dung_id', $nguoiDungId)
                        ->where('da_doc', false)
                        ->count();
        
        return response()->json([
            'unread_count' => $count,
        ]);
    }
    
    /**
     * Xóa thông báo
     *
     * @param Request $request
     * @param int $id ID của thông báo
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(Request $request, $id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        
        if (!$nguoiDungId) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập',
            ], 401);
        }
        
        $thongBao = ThongBao::where('id', $id)
                          ->where('nguoi_dung_id', $nguoiDungId)
                          ->first();
        
        if (!$thongBao) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo',
            ], 404);
        }
        
        // Xóa thông báo
        $thongBao->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa thông báo thành công',
        ]);
    }
    
    /**
     * Tạo thông báo mới
     *
     * @param int $nguoiDungId ID của người dùng nhận thông báo
     * @param string $tieuDe Tiêu đề thông báo
     * @param string $noiDung Nội dung thông báo
     * @param string $loai Loại thông báo
     * @param string|null $url Đường dẫn khi click vào thông báo
     * @return ThongBao
     */
    public static function taoThongBao($nguoiDungId, $tieuDe, $noiDung, $loai = 'he_thong', $url = null)
    {
        return ThongBao::create([
            'nguoi_dung_id' => $nguoiDungId,
            'tieu_de' => $tieuDe,
            'noi_dung' => $noiDung,
            'loai' => $loai,
            'da_doc' => false,
            'url' => $url,
        ]);
    }

    /**
     * Hiển thị trang danh sách thông báo
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = auth()->id();
        
        // Lấy danh sách thông báo
        $query = ThongBao::where('nguoi_dung_id', $nguoiDungId)
                       ->orderBy('created_at', 'desc');
                       
        // Lọc theo đã đọc / chưa đọc nếu có
        if ($request->has('da_doc')) {
            $query->where('da_doc', $request->da_doc == 1);
        }
        
        // Lọc theo loại thông báo nếu có
        if ($request->has('loai') && !empty($request->loai)) {
            $query->where('loai', $request->loai);
        }
        
        // Phân trang
        $thongBaos = $query->paginate(10);
        
        return view('notifications.index', compact('thongBaos'));
    }
    
    /**
     * Hiển thị chi tiết thông báo
     */
    public function show($id)
    {
        // Lấy ID người dùng hiện tại
        $nguoiDungId = auth()->id();
        
        // Lấy thông tin thông báo
        $thongBao = ThongBao::where('id', $id)
                          ->where('nguoi_dung_id', $nguoiDungId)
                          ->firstOrFail();
        
        // Đánh dấu thông báo là đã đọc
        if (!$thongBao->da_doc) {
            $thongBao->da_doc = true;
            $thongBao->ngay_doc = Carbon::now();
            $thongBao->save();
        }
        
        return view('notifications.show', compact('thongBao'));
    }

    /**
     * Xóa thông báo
     *
     * @param int $id ID của thông báo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Lấy ID người dùng hiện tại
        $nguoiDungId = auth()->id();
        
        // Lấy thông tin thông báo
        $thongBao = ThongBao::where('id', $id)
                          ->where('nguoi_dung_id', $nguoiDungId)
                          ->firstOrFail();
        
        // Xóa thông báo
        $thongBao->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'Thông báo đã được xóa thành công');
    }
}
