<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BaiHoc extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'bai_hocs';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'khoa_hoc_id',
        'tieu_de',
        'mo_ta',
        'noi_dung',
        'so_thu_tu',
        'loai',
        'url_video',
        'thoi_luong',
        'trang_thai',
    ];

    /**
     * Các cột thời gian tùy chỉnh
     *
     * @var array
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ với khóa học
     */
    public function khoaHoc(): BelongsTo
    {
        return $this->belongsTo(KhoaHoc::class, 'khoa_hoc_id');
    }

    /**
     * Quan hệ với bài tập
     */
    public function baiTaps(): HasMany
    {
        return $this->hasMany(BaiTap::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với tài liệu bổ trợ
     */
    public function taiLieuBoTros(): HasMany
    {
        return $this->hasMany(TaiLieuBoTro::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với bình luận
     */
    public function binhLuans(): HasMany
    {
        return $this->hasMany(BinhLuan::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với tiến độ bài học
     */
    public function tienDoBaiHocs(): HasMany
    {
        return $this->hasMany(TienDoBaiHoc::class, 'bai_hoc_id');
    }

    /**
     * Quan hệ với lớp học (nhiều-nhiều)
     */
    public function lopHocs(): BelongsToMany
    {
        return $this->belongsToMany(LopHoc::class, 'bai_hoc_lops', 'bai_hoc_id', 'lop_hoc_id')
            ->withPivot(['so_thu_tu', 'ngay_bat_dau'])
            ->withTimestamps('tao_luc', 'cap_nhat_luc');
    }
    public function lopHoc(): BelongsToMany
    {
        return $this->belongsToMany(LopHoc::class, 'bai_hoc_lops', 'bai_hoc_id', 'lop_hoc_id')
            ->withPivot(['so_thu_tu', 'ngay_bat_dau'])
            ->withTimestamps('tao_luc', 'cap_nhat_luc');
    }

    /**
     * Quan hệ với bài học lớp
     */
    public function baiHocLops(): HasMany
    {
        return $this->hasMany(BaiHocLop::class, 'bai_hoc_id');
    }
    
    /**
     * Quan hệ với các tệp đính kèm
     */
    public function files()
    {
        return $this->hasMany(Files::class, 'bai_hoc_id');
    }

}