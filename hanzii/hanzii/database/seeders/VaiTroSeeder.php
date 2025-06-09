<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaiTros = [
            [
                'ten' => 'admin',
                'mo_ta' => 'Quản trị viên hệ thống',
                'he_so_luong' => 0,
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'giao_vien',
                'mo_ta' => 'Giáo viên giảng dạy',
                'he_so_luong' => 1.0,
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'tro_giang',
                'mo_ta' => 'Trợ giảng hỗ trợ giáo viên',
                'he_so_luong' => 0.6,
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'hoc_vien',
                'mo_ta' => 'Học viên tham gia khóa học',
                'he_so_luong' => 0,
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
        ];

        DB::table('vai_tros')->insert($vaiTros);
    }
} 