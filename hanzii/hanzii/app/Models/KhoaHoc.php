<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KhoaHoc extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'khoa_hocs';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'ten',
        'mo_ta',
        'hinh_anh',
        'hoc_phi',
        'tong_so_bai',
        'thoi_gian_hoan_thanh',
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
     * Quan hệ với lớp học
     */
    public function lopHocs(): HasMany
    {
        return $this->hasMany(LopHoc::class, 'khoa_hoc_id');
    }

    /**
     * Quan hệ với bài học
     */
    public function baiHocs(): HasMany
    {
        return $this->hasMany(BaiHoc::class, 'khoa_hoc_id');
    }

   
}
