<?php

namespace App\Http\Controllers;

use App\Models\LienHe;
use App\Models\NguoiDung;
use App\Notifications\LienHeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LienHeController extends Controller
{
    /**
     * Hiển thị trang liên hệ
     *
     * @return \Illuminate\View\View
     */
 

    /**
     * Lưu thông tin liên hệ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'chu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên của bạn',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'chu_de.required' => 'Vui lòng nhập chủ đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung tin nhắn',
        ]);

        // Tạo liên hệ mới
        $lienHe = LienHe::create([
            'ho_ten' => $validated['ho_ten'],
            'email' => $validated['email'],
            'chu_de' => $validated['chu_de'],
            'noi_dung' => $validated['noi_dung'],
            'trang_thai' => 'chua_doc',
        ]);

        // Gửi thông báo cho admin
            $this->guiThongBaoChoAdmin($lienHe);
        // Chuyển hướng về trang chủ với thông báo thành công
        return redirect()->route('welcome')->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất!');
    }
    // Nhập xong thông tin liên hệ thì gửi cho mấy thằng admin  để thằng admin nhận được. 
    private function guiThongBaoChoAdmin($lienHe)
    {
        // Kiểm tra cả hai loại vai trò (quan_tri và admin) để đảm bảo tìm đúng người dùng
        $admins = NguoiDung::whereHas('vaiTro', function($query) {
            $query->whereIn('ten', ['quan_tri', 'admin']);
        })->get(); 
        // Gửi thông báo đến từng admin
        foreach ($admins as $admin) {
            try {
                // Sử dụng hệ thống thông báo của Laravel 
                // Điều này tự động tạo bản ghi trong bảng notifications và gửi email
                $admin->notify(new LienHeNotification($lienHe));        
            } catch (\Exception $e) {
                Log::error('Lỗi khi gửi thông báo cho admin ' . $admin->id . ': ' . $e->getMessage());
            }
        }
    }

    // hiển thị tất cả những yêu cầu 
    public function danhSach()
    {
        $lienHes = LienHe::orderBy('tao_luc', 'desc')->paginate(10);
        return view('admin.lien-he.index', compact('lienHes'));
    }

    //Hiển thị chi tiết liên hệ
    public function show($id)
    {
        $lienHe = LienHe::findOrFail($id);
        // Nếu liên hệ chưa đọc, cập nhật trạng thái thành đã đọc bởi vì bấm vô rồi 
        if ($lienHe->trang_thai == 'chua_doc') {
            $lienHe->update(['trang_thai' => 'da_doc']);
        }
        return view('admin.lien-he.show', compact('lienHe'));
    }

 
    // hàm gửi mail cho người liên hệ
    public function sendResponse(Request $request, $id) // lấy dữ liệu từ 2 ô nhập và id khi nhấn vô 
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'subject.required' => 'Vui lòng nhập tiêu đề email',
            'message.required' => 'Vui lòng nhập nội dung phản hồi',
        ]);
        
        // Tìm id 
        $lienHe = LienHe::findOrFail($id);
        
        try {
            // Gửi mail với nội dung được lấy từ cái ô nhập message 
            Mail::send('emails.response', [ // view/email/response
                'noiDungPhanHoi' => $validated['message'],
                'lienHe' => $lienHe
            ], function ($mail) use ($lienHe, $validated) {
                $mail->to($lienHe->email, $lienHe->ho_ten)
                    ->subject($validated['subject'])
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            // Cập nhật trạng thái liên hệ
            $lienHe->update(['trang_thai' => 'da_phan_hoi']);    
            return redirect()->route('admin.lien-he.show', $id)
                ->with('success', 'Đã gửi email phản hồi thành công đến ' . $lienHe->email);
        } catch (\Exception $e) { 
            return redirect()->route('admin.lien-he.show', $id)
                ->with('error', 'Không thể gửi email phản hồi: ' . $e->getMessage());
        }
    }

} 