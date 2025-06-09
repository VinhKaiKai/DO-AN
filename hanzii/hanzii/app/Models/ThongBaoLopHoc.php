<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBaoLopHoc extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'thong_bao_lop_hocs';

    /**
     * Các trường có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'lop_hoc_id',
        'tieu_de',
        'noi_dung',
        'nguoi_tao',
        'nguoi_sua',
        'ngay_hieu_luc',
        'ngay_het_han',
        'trang_thai',
        'dinh_kem',
        'file_dinh_kem',
        'hien_thi',
    ];

    /**
     * Các trường sẽ được chuyển đổi thành kiểu dữ liệu cụ thể
     *
     * @var array
     */
    protected $casts = [
        'hien_thi' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Các trạng thái thông báo
     */
    const TRANG_THAI_KICH_HOAT = 1;
    const TRANG_THAI_KHONG_KICH_HOAT = 0;

    /**
     * Lấy lớp học liên quan đến thông báo
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }

    /**
     * Lấy người dùng tạo thông báo
     */
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 