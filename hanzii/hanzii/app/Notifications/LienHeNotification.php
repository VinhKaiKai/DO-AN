<?php

namespace App\Notifications;

use App\Models\LienHe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LienHeNotification extends Notification
{
   

    protected $lienHe;

    // khi truyền đối tượng lienhe vô cái cóntruc này sẽ giúp sử dụng các trường thông tin của nó như tên email, nội dung,...
    // có thể sử dụng trong các hàm kia.
    public function __construct(LienHe $lienHe)
    {
        $this->lienHe = $lienHe;
    }

    public function via($notifiable)
    {
        // nó sẽ làm 2 việc
        //Lưu thông báo vào bảng notifications
        //Gửi email tới admin (nếu họ có email)
        return ['database', 'mail'];
    }

   
    // này sẽ gửi mail từ liên hệ đến mail của admin.
    //hàm này được gọi tự động nếu trong via có cái mail kia.  
    public function toMail($notifiable)
    {
        // cái này để tạo nội dung gửi đi. 
        //dùng cái mailMessage để xây dựng gmail thành câu truc html.
        return (new MailMessage)
            ->subject('Tin nhắn liên hệ mới từ ' . $this->lienHe->ho_ten)
            ->greeting('Xin chào!')
            ->line('Bạn vừa nhận được một tin nhắn liên hệ mới từ ' . $this->lienHe->ho_ten . '.')
            ->line('Chủ đề: ' . $this->lienHe->chu_de)
            ->line('Email: ' . $this->lienHe->email)
            ->action('Xem chi tiết', route('admin.lien-he.show', $this->lienHe->id))
            ->line('Cảm ơn bạn đã sử dụng hệ thống quản lý Trung tâm Tiếng Trung Hanzii!');
    }

    
    // lưu vô bảng notification để khi dùng tới.
    public function toDatabase($notifiable)
    {
        // tạo 1 mảng với nội dung lưu vô cột data. 
        return [
            'tieu_de' => 'Tin nhắn mới từ: ' . $this->lienHe->ho_ten,
            'noi_dung' => 'Chủ đề: ' . $this->lienHe->chu_de,
            'url' => route('admin.lien-he.show', $this->lienHe->id)
        ];
    }

    // hàm này dùng để hiển thị các thông tin ra frondend khi cần dùng đến. 
    public function toArray($notifiable)
    {
        return [
            'id' => $this->lienHe->id,
            'ho_ten' => $this->lienHe->ho_ten,
            'chu_de' => $this->lienHe->chu_de,
            'loai' => 'lien_he',
            'url' => route('admin.lien-he.show', $this->lienHe->id)
        ];
    }
} 