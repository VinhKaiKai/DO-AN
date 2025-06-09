<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    protected $table = 'files';
    
    protected $fillable = [
        'ten_goc',
        'ten_luu',
        'duong_dan',
        'loai',
        'kich_thuoc',
        'nguoi_tao',
        'bai_tap_id',
        'bai_hoc_id',
        'bai_tap_da_nop_id',
        'tai_lieu_id',
        'thong_bao_id'
    ];

    /**
     * Lấy bài tập liên quan
     */
    public function baiTap()
    {
        return $this->belongsTo(BaiTap::class, 'bai_tap_id');
    }

    /**
     * Lấy bài học liên quan
     */
    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'bai_hoc_id');
    }

    /**
     * Lấy bài tập đã nộp liên quan
     */
    public function baiTapDaNop()
    {
        return $this->belongsTo(BaiTapDaNop::class, 'bai_tap_da_nop_id');
    }

    /**
     * Lấy tài liệu liên quan
     */
    public function taiLieu()
    {
        return $this->belongsTo(TaiLieuBoTro::class, 'tai_lieu_id');
    }

    /**
     * Lấy thông báo liên quan
     */
    public function thongBao()
    {
        return $this->belongsTo(ThongBaoLopHoc::class, 'thong_bao_id');
    }

    /**
     * Lấy người tạo
     */
    public function nguoiTaoFile()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao');
    }
} 