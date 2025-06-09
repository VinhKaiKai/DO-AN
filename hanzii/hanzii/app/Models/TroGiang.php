<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TroGiang extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'tro_giangs';

    /**
     * Các trường có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'nguoi_dung_id',
        'bang_cap',
        'trinh_do',
        'so_nam_kinh_nghiem',
    ];

    public $timestamps = false;

    /**
     * Quan hệ với người dùng
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với các lớp học mà trợ giảng này đang dạy
     */
    public function lopHocs(): HasMany
    {
        return $this->hasMany(LopHoc::class, 'tro_giang_id');
    }

} 