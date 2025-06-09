<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaiTro extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'vai_tros';

    /**
     * Các cột có thể gán giá trị
     *
     * @var array
     */
    protected $fillable = [
        'ten',
        'mo_ta',
        'he_so_luong',
    ];

    /**
     * Các cột thời gian tùy chỉnh
     *
     * @var array
     */
    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'cap_nhat_luc';

    /**
     * Quan hệ 1-n với người dùng (một vai trò có nhiều người dùng)
     */
    public function nguoiDungs(): HasMany
    {
        return $this->hasMany(NguoiDung::class, 'vai_tro_id');
    }

    /**
     * Quan hệ n-n với quyền
     */
    public function quyens(): BelongsToMany
    {
        return $this->belongsToMany(Quyen::class, 'quyen_vai_tros', 'vai_tro_id', 'quyen_id')
            ->withTimestamps('tao_luc', 'cap_nhat_luc');
    }
}
