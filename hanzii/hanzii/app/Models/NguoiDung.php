<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'nguoi_dungs';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'ho',
        'ten',
        'email',
        'so_dien_thoai',
        'mat_khau',
        'loai_tai_khoan',
        'vai_tro_id',
        'dia_chi',
        'anh_dai_dien',
    ];

    /**
     * Các cột thời gian tùy chỉnh
     *
     * @var array
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    /**
     * Quan hệ 1-1 với giáo viên
     */
    public function giaoVien(): HasOne
    {
        return $this->hasOne(GiaoVien::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ 1-1 với trợ giảng
     */
    public function troGiang(): HasOne
    {
        return $this->hasOne(TroGiang::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ 1-1 với học viên
     */
    public function hocVien(): HasOne
    {
        return $this->hasOne(HocVien::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với vai trò (1-n: một người dùng thuộc về một vai trò)
     */
    public function vaiTro(): BelongsTo
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    /**
     * Kiểm tra người dùng có vai trò cụ thể không
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (!$this->vaiTro) {
            return false;
        }
        
        if (is_array($roles)) {
            return in_array($this->vaiTro->ten, $roles);
        }
        
        return $this->vaiTro->ten === $roles;
    }

    /**
     * Quan hệ 1-n với bình luận
     */
    public function binhLuans(): HasMany
    {
        return $this->hasMany(BinhLuan::class, 'nguoi_dung_id');
    }

    /**
     * Accessor để lấy họ tên đầy đủ
     */
    public function getHoTenAttribute()
    {
        return "{$this->ho} {$this->ten}";
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
