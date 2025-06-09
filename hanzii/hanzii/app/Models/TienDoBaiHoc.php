<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TienDoBaiHoc extends Model
{
    use HasFactory;

    protected $table = 'tien_do_bai_hocs';

    protected $fillable = [
        'bai_hoc_id',
        'hoc_vien_id',
        'ngay_hoan_thanh',
        'diem',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_hoan_thanh' => 'datetime',
    ];

    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ với bài học
     */
    public function baiHoc(): BelongsTo
    {
        return $this->belongsTo(BaiHoc::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với học viên
     */
    public function hocVien(): BelongsTo
    {
        return $this->belongsTo(HocVien::class, 'hoc_vien_id');
    }
}
