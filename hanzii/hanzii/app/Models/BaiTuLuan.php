<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaiTuLuan extends Model
{
    use HasFactory;

    protected $table = 'bai_tu_luans';

    protected $fillable = [
        'hoc_vien_id',
        'bai_tap_id',
        'lop_hoc_id',
        'noi_dung',
        'trang_thai',
        'diem',
        'diem_toi_da',
        'nhan_xet',
        'nguoi_cham_id',
        'ngay_nop',
        'ngay_cham',
    ];

    protected $casts = [
        'ngay_nop' => 'datetime',
        'ngay_cham' => 'datetime',
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
     * Quan hệ với bài tập
     */
    public function baiTap(): BelongsTo
    {
        return $this->belongsTo(BaiTap::class, 'bai_tap_id');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Quan hệ với người chấm (giáo viên)
     */
    public function nguoiCham(): BelongsTo
    {
        return $this->belongsTo(GiaoVien::class, 'nguoi_cham_id');
    }
}
