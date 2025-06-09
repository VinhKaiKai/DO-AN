<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileBaiTap extends Model
{
    use HasFactory;

    protected $table = 'file_bai_taps';

    protected $fillable = [
        'hoc_vien_id',
        'bai_tap_id',
        'lop_hoc_id',
        'ten_file',
        'duong_dan_file',
        'loai_file',
        'kich_thuoc_file',
        'ngay_nop',
        'diem',
        'nhan_xet',
        'trang_thai',
        'nguoi_cham_id',
    ];

    protected $casts = [
        'ngay_nop' => 'datetime',
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
     * Quan hệ với người chấm
     */
    public function nguoiCham(): BelongsTo
    {
        return $this->belongsTo(GiaoVien::class, 'nguoi_cham_id');
    }
}
