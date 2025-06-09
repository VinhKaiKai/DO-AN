<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luans';

    protected $fillable = [
        'nguoi_dung_id',
        'bai_hoc_id',
        'lop_hoc_id',
        'noi_dung',
        'da_phan_hoi',
        'binh_luan_goc_id',
    ];

    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ với người dùng
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bài học
     */
    public function baiHoc(): BelongsTo
    {
        return $this->belongsTo(BaiHoc::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Quan hệ với bình luận gốc
     */
    public function binhLuanGoc(): BelongsTo
    {
        return $this->belongsTo(BinhLuan::class, 'binh_luan_goc_id');
    }

    /**
     * Quan hệ với các phản hồi
     */
    public function phanHois(): HasMany
    {
        return $this->hasMany(BinhLuan::class, 'binh_luan_goc_id');
    }
}
