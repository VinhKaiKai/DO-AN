<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     */
    protected $table = 'lien_hes';

    /**
     * Các cột có thể gán giá trị
     */
    protected $fillable = [
        'ho_ten',
        'email',
        'chu_de',
        'noi_dung',
        'trang_thai' // 'chua_doc', 'da_doc', 'da_phan_hoi'
    ];

    /**
     * Các cột thời gian tùy chỉnh
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';
} 