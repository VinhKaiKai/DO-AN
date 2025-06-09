<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiaoVien extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'giao_viens';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'nguoi_dung_id',
        'bang_cap',
        'chuyen_mon',
        'so_nam_kinh_nghiem',
    ];

    /**
     * Các cột thời gian tùy chỉnh
     *
     * @var array
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Các thuộc tính sẽ ép kiểu
     *
     * @var array
     */
    protected $casts = [
        'bang_cap' => 'array',
    ];

    /**
     * Quan hệ với người dùng
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với lớp học (giáo viên phụ trách)
     */
    public function lopHocs(): HasMany
    {
        return $this->hasMany(LopHoc::class, 'giao_vien_id');
    }

    /**
     * Quan hệ với bài học
     */
    public function baiHocs(): HasMany
    {
        return $this->hasMany(BaiHoc::class, 'giao_vien_id');
    }

    /**
     * Quan hệ với bài tập
     */
    public function baiTaps(): HasMany
    {
        return $this->hasMany(BaiTap::class, 'giao_vien_id');
    }

    /**
     * Lấy toàn bộ số lớp đang dạy
     */
    public function soLopDangDay()
    {
        return $this->lopHocs()
            ->whereIn('trang_thai', ['dang_dien_ra', 'sap_khai_giang'])
            ->count();
    }

    /**
     * Lấy toàn bộ số học viên đang dạy
     */
    public function soHocVienDangDay()
    {
        return DangKyHoc::whereIn('lop_hoc_id', $this->lopHocs()->pluck('id'))
            ->whereIn('trang_thai', ['dang_hoc', 'da_duyet'])
            ->count();
    }

    /**
     * Lấy toàn bộ số học viên đã dạy
     */
    public function soHocVienDaDayXong()
    {
        return DangKyHoc::whereIn('lop_hoc_id', $this->lopHocs()->where('trang_thai', 'da_hoan_thanh')->pluck('id'))
            ->where('trang_thai', 'da_hoan_thanh')
            ->count();
    }

    /**
     * Accessor để lấy họ tên đầy đủ
     */
    public function getHoTenAttribute()
    {
        return $this->nguoiDung ? "{$this->nguoiDung->ho} {$this->nguoiDung->ten}" : '';
    }
} 