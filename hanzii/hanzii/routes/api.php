<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes cho thông báo
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
});

// Route API cho thanh toán học phí
Route::get('/hoc-vien/{id}/lop-hoc-va-hoc-phi', function($id) {
    $hocVien = \App\Models\HocVien::findOrFail($id);
    
    // Lấy lớp học mà học viên đang học (lấy lớp học mới nhất)
    $lopHoc = $hocVien->lopHocs()->latest('tao_luc')->first();
    
    if (!$lopHoc) {
        return response()->json(['message' => 'Học viên không có lớp học'], 404);
    }
    
    return response()->json([
        'lop_hoc_id' => $lopHoc->id,
        'ten_lop' => $lopHoc->ten,
        'ma_lop' => $lopHoc->ma_lop,
        'hoc_phi' => $lopHoc->hoc_phi ?? 0
    ]);
});

Route::get('/lop-hoc/{id}/hoc-phi', function($id) {
    $lopHoc = \App\Models\LopHoc::findOrFail($id);
    
    return response()->json([
        'hoc_phi' => $lopHoc->khoaHoc->hoc_phi ?? 0
    ]);
});