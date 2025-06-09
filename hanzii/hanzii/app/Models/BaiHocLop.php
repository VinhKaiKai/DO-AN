<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaiHocLop extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'bai_hoc_lops';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'lop_hoc_id',
        'bai_hoc_id',
        'so_thu_tu',
        'ngay_bat_dau',
    ];

    /**
     * Các cột thời gian tùy chỉnh
     *
     * @var array
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Quan hệ với bài học
     */
    public function baiHoc(): BelongsTo
    {
        return $this->belongsTo(BaiHoc::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với tiến độ bài học
     */
    public function tienDos()
    {
        return $this->hasMany(TienDoBaiHoc::class, 'bai_hoc_id', 'bai_hoc_id');
    }

    public function lopHocs()
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }
}
