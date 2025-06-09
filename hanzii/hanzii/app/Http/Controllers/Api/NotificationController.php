<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\NguoiDung;

class NotificationController extends Controller
{
    /**
     * Lấy user hiện tại từ session hoặc Auth
     *
     */
    protected function getCurrentUser(Request $request)
    {
        if ($request->session()->has('nguoi_dung_id')) {
            $userId = $request->session()->get('nguoi_dung_id');
            return NguoiDung::find($userId);
        }

        return Auth::user();
    }

    /**
     * Lấy danh sách thông báo
     */
    public function index(Request $request)
    {
        $user = $this->getCurrentUser($request);
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            // ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Lấy số lượng thông báo chưa đọc
     */
    public function unreadCount(Request $request)
    {
        $user = $this->getCurrentUser($request);
        $unreadCount = DatabaseNotification::where('notifiable_id', $user->id)
            // ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')// điều kiện để lấy chưa đọc. nếu đọc rồi sẽ có thời gian. 
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Đánh dấu 1 thông báo đã đọc
     */
    public function markAsRead($id, Request $request)
    {
        $user = $this->getCurrentUser($request);
        $notification = DatabaseNotification::find($id);
        if ($notification && $notification->notifiable_id == $user->id) {
            $notification->markAsRead();
            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu thông báo là đã đọc'
            ]);
        }

        // return response()->json([
        //     'success' => false,
        //     'message' => 'Không tìm thấy thông báo'
        // ], 404);
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead(Request $request)
    {
        $user = $this->getCurrentUser($request);
        DatabaseNotification::where('notifiable_id', $user->id)
            // ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu tất cả thông báo là đã đọc'
        ]);
    }
}