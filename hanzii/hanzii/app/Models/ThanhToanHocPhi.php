<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThanhToanHocPhi extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'thanh_toan_hoc_phis';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'hoc_vien_id',
        'lop_hoc_id',
        'so_tien',
        'phuong_thuc_thanh_toan',
        'trang_thai',
        'ngay_thanh_toan',
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
     * Quan hệ với học viên
     */
    public function hocVien(): BelongsTo
    {
        return $this->belongsTo(HocVien::class, 'hoc_vien_id');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Trả về text hiển thị tương ứng với trạng thái
     * 
     * @return string
     */
    public function getTrangThaiTextAttribute()
    {
        $trangThaiMap = [
            'chua_thanh_toan' => 'Chưa thanh toán',
            'da_thanh_toan' => 'Đã thanh toán',
            'da_huy' => 'Đã hủy'
        ];
        
        return $trangThaiMap[$this->trang_thai] ?? 'Không xác định';
    }

    /**
     * Trả về text hiển thị tương ứng với phương thức thanh toán
     * 
     * @return string
     */
    public function getPhuongThucThanhToanTextAttribute()
    {
        $phuongThucMap = [
            'tien_mat' => 'Tiền mặt',
            'chuyen_khoan' => 'Chuyển khoản'
        ];
        
        return $phuongThucMap[$this->phuong_thuc_thanh_toan] ?? 'Không xác định';
    }
}
