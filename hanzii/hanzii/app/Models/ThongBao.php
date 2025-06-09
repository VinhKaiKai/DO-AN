<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    /**
     * Tên bảng
     */
    protected $table = 'thong_baos';

    /**
     * Các thuộc tính có thể gán
     */
    protected $fillable = [
        'nguoi_dung_id',
        'tieu_de',
        'noi_dung',
        'loai',
        'da_doc',
        'url',
    ];

    /**
     * Các thuộc tính sẽ cast về kiểu dữ liệu phù hợp
     */
    protected $casts = [
        'da_doc' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Thiết lập quan hệ với người dùng
     */
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
    }
} 