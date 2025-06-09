<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaiTap extends Model
{
    use HasFactory;

    protected $table = 'bai_taps';

    protected $fillable = [
        'bai_hoc_id',
        'tieu_de',
        'loai',
        'noi_dung',
        'diem_toi_da',
        'mo_ta',
        'han_nop',
        'file_dinh_kem',
        'ten_file',
        'trang_thai',
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
     * Quan hệ với bài tự luận
     */
    public function baiTuLuans(): HasMany
    {
        return $this->hasMany(BaiTuLuan::class, 'bai_tap_id');
    }

    /**
     * Quan hệ với file bài tập
     */
    public function fileBaiTaps(): HasMany
    {
        return $this->hasMany(FileBaiTap::class, 'bai_tap_id');
    }

    /**
     * Quan hệ với lịch sử làm bài
     */
    public function lichSuLamBais(): HasMany
    {
        return $this->hasMany(LichSuLamBai::class, 'bai_tap_id');
    }

    /**
     * Quan hệ với nộp bài tập
     */
    public function nopBaiTaps(): HasMany
    {
        return $this->baiTapDaNops();
    }

    /**
     * Quan hệ với bài đã nộp
     */
    public function baiTapDaNops(): HasMany
    {
        return $this->hasMany(BaiTapDaNop::class, 'bai_tap_id');
    }
}
