<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DangKyHoc extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_hocs';

    protected $fillable = [
        'hoc_vien_id',
        'lop_hoc_id',
        'ngay_dang_ky',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_dang_ky' => 'date',
    ];

    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ với học viên
     */
    public function hocVien(): BelongsTo
    {
        return $this->belongsTo(HocVien::class, 'hoc_vien_id');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Quan hệ với thanh toán
     */
    // public function thanhToan(): HasOne
    // {
    //     return $this->hasOne(ThanhToan::class, 'dang_ky_id');
    // }
}