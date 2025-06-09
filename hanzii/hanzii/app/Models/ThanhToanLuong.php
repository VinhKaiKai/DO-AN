<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThanhToanLuong extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'thanh_toan_luongs';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'lop_hoc_id',
        'giao_vien_id',
        'tro_giang_id',
        'he_so_luong_giao_vien',
        'he_so_luong_tro_giang',
        'tong_tien_thu',
        'tien_luong_giao_vien',
        'tien_luong_tro_giang',
        'trang_thai_giao_vien',
        'trang_thai_tro_giang',
        'ngay_thanh_toan_giao_vien',
        'ngay_thanh_toan_tro_giang',
        'ghi_chu',
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
     * Quan hệ với giáo viên
     */
    public function giaoVien(): BelongsTo
    {
        return $this->belongsTo(GiaoVien::class, 'giao_vien_id');
    }

    /**
     * Quan hệ với trợ giảng
     */
    public function troGiang(): BelongsTo
    {
        return $this->belongsTo(TroGiang::class, 'tro_giang_id');
    }

    /**
     * Kiểm tra xem đã thanh toán đầy đủ chưa
     */
    public function daThanhToanDayDu(): bool
    {
        return $this->trang_thai_giao_vien === 'da_thanh_toan' && $this->trang_thai_tro_giang === 'da_thanh_toan';
    }

    /**
     * Lấy text hiển thị trạng thái giáo viên
     */
    public function getTrangThaiGiaoVienTextAttribute(): string
    {
        $trangThaiMap = [
            'chua_thanh_toan' => 'Chưa trả lương',
            'da_thanh_toan' => 'Đã trả lương',
        ];
        
        return $trangThaiMap[$this->trang_thai_giao_vien] ?? 'Không xác định';
    }

    /**
     * Lấy text hiển thị trạng thái trợ giảng
     */
    public function getTrangThaiTroGiangTextAttribute(): string
    {
        $trangThaiMap = [
            'chua_thanh_toan' => 'Chưa trả lương',
            'da_thanh_toan' => 'Đã trả lương',
        ];
        
        return $trangThaiMap[$this->trang_thai_tro_giang] ?? 'Không xác định';
    }
}
