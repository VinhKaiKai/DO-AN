<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NguoiDungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin
        $adminId = DB::table('nguoi_dungs')->insertGetId([
            'ho' => 'Admin',
            'ten' => 'Hệ thống',
            'email' => 'admin@hanzii.com',
            'so_dien_thoai' => '0123456789',
            'mat_khau' => Hash::make('admin123'),
            'loai_tai_khoan' => 'giao_vien', // Giáo viên với vai trò admin
            'dia_chi' => 'Hà Nội',
            'tao_luc' => now(),
            'cap_nhat_luc' => now(),
        ]);

        // Tạo giáo viên
        $giaoVienIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $nguoiDungId = DB::table('nguoi_dungs')->insertGetId([
                'ho' => 'Giáo Viên',
                'ten' => "Số $i",
                'email' => "giaovien$i@hanzii.com",
                'so_dien_thoai' => "01234567$i" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'mat_khau' => Hash::make('password'),
                'loai_tai_khoan' => 'giao_vien',
                'dia_chi' => 'Hà Nội',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            $giaoVienIds[] = DB::table('giao_viens')->insertGetId([
                'nguoi_dung_id' => $nguoiDungId,
                'bang_cap' => json_encode(['Thạc sĩ Giáo dục', 'Cử nhân Ngôn ngữ học']),
                'chuyen_mon' => 'Tiếng Nhật',
                'so_nam_kinh_nghiem' => rand(3, 15),
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            // Gán vai trò giáo viên
            DB::table('vai_tro_nguoi_dungs')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'vai_tro_id' => 2, // ID vai trò giáo viên
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);
        }

        // Tạo trợ giảng
        $troGiangIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $nguoiDungId = DB::table('nguoi_dungs')->insertGetId([
                'ho' => 'Trợ Giảng',
                'ten' => "Số $i",
                'email' => "trogiang$i@hanzii.com",
                'so_dien_thoai' => "09876543$i" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'mat_khau' => Hash::make('password'),
                'loai_tai_khoan' => 'tro_giang',
                'dia_chi' => 'Hà Nội',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            $troGiangIds[] = DB::table('tro_giangs')->insertGetId([
                'nguoi_dung_id' => $nguoiDungId,
                'bang_cap' => json_encode(['Cử nhân Ngôn ngữ học']),
                'trinh_do' => 'Đại học',
                'so_nam_kinh_nghiem' => rand(1, 5),
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            // Gán vai trò trợ giảng
            DB::table('vai_tro_nguoi_dungs')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'vai_tro_id' => 3, // ID vai trò trợ giảng
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);
        }

        // Tạo học viên
        $hocVienIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $nguoiDungId = DB::table('nguoi_dungs')->insertGetId([
                'ho' => 'Học Viên',
                'ten' => "Số $i",
                'email' => "hocvien$i@gmail.com",
                'so_dien_thoai' => "098765$i" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'mat_khau' => Hash::make('password'),
                'loai_tai_khoan' => 'hoc_vien',
                'dia_chi' => 'Hà Nội',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            $hocVienIds[] = DB::table('hoc_viens')->insertGetId([
                'nguoi_dung_id' => $nguoiDungId,
                'trinh_do_hoc_van' => 'Đại học',
                'ngay_sinh' => date('Y-m-d', strtotime('-' . rand(18, 40) . ' years')),
                'trang_thai' => 'hoat_dong',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);

            // Gán vai trò học viên
            DB::table('vai_tro_nguoi_dungs')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'vai_tro_id' => 4, // ID vai trò học viên
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ]);
        }

        // Tạo hồ sơ giáo viên cho admin
        $giaoVienAdminId = DB::table('giao_viens')->insertGetId([
            'nguoi_dung_id' => $adminId,
            'bang_cap' => json_encode(['Tiến sĩ Quản trị']),
            'chuyen_mon' => 'Quản lý hệ thống',
            'so_nam_kinh_nghiem' => 10,
            'tao_luc' => now(),
            'cap_nhat_luc' => now(),
        ]);

        // Gán vai trò admin
        DB::table('vai_tro_nguoi_dungs')->insert([
            'nguoi_dung_id' => $adminId,
            'vai_tro_id' => 1, // ID vai trò admin
            'tao_luc' => now(),
            'cap_nhat_luc' => now(),
        ]);
    }
} 