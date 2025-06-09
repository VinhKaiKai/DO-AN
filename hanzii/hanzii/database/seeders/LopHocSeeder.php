<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LopHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách ID của khóa học
        $khoaHocIds = DB::table('khoa_hocs')->pluck('id')->toArray();
        
        // Lấy danh sách ID giáo viên
        $giaoVienIds = DB::table('giao_viens')
            ->join('vai_tro_nguoi_dungs', 'giao_viens.nguoi_dung_id', '=', 'vai_tro_nguoi_dungs.nguoi_dung_id')
            ->where('vai_tro_nguoi_dungs.vai_tro_id', 2) // ID vai trò giáo viên
            ->pluck('giao_viens.id')
            ->toArray();

        // Lấy danh sách ID trợ giảng
        $troGiangIds = DB::table('tro_giangs')
            ->join('vai_tro_nguoi_dungs', 'tro_giangs.nguoi_dung_id', '=', 'vai_tro_nguoi_dungs.nguoi_dung_id')
            ->where('vai_tro_nguoi_dungs.vai_tro_id', 3) // ID vai trò trợ giảng
            ->pluck('tro_giangs.id')
            ->toArray();

        // Thời gian bắt đầu lớp học từ tháng trước đến 3 tháng tới
        $startDates = [
            date('Y-m-d', strtotime('-1 month')),
            date('Y-m-d', strtotime('-2 weeks')),
            date('Y-m-d', strtotime('-1 week')),
            date('Y-m-d', strtotime('now')),
            date('Y-m-d', strtotime('+1 week')),
            date('Y-m-d', strtotime('+2 weeks')),
            date('Y-m-d', strtotime('+1 month')),
            date('Y-m-d', strtotime('+2 months')),
            date('Y-m-d', strtotime('+3 months')),
        ];
        
        // Danh sách lịch học mẫu
        $lichHocMau = [
            'Thứ 2, Thứ 4 (19:00 - 21:00)',
            'Thứ 3, Thứ 5 (19:00 - 21:00)',
            'Thứ 2, Thứ 4, Thứ 6 (18:30 - 20:30)',
            'Thứ 7, Chủ nhật (9:00 - 12:00)',
            'Thứ 7, Chủ nhật (14:00 - 17:00)',
            'Thứ 7 (8:30 - 12:30)',
            'Chủ nhật (8:30 - 12:30)',
        ];

        // Tạo 20 lớp học ngẫu nhiên
        $lopHocs = [];
        for ($i = 1; $i <= 20; $i++) {
            $khoaHocId = $khoaHocIds[array_rand($khoaHocIds)];
            $giaoVienId = $giaoVienIds[array_rand($giaoVienIds)];
            $troGiangId = $troGiangIds[array_rand($troGiangIds)];
            $startDate = $startDates[array_rand($startDates)];
            
            // Lấy thông tin khóa học để tính thời gian kết thúc
            $khoaHoc = DB::table('khoa_hocs')->where('id', $khoaHocId)->first();
            
            // Tính thời gian kết thúc dựa vào thời gian hoàn thành khóa học
            $months = intval(substr($khoaHoc->thoi_gian_hoan_thanh, 0, 1));
            $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $months . ' months'));
            
            // Xác định trạng thái lớp học dựa vào ngày bắt đầu và kết thúc
            $trangThai = 'sap_khai_giang';
            if (strtotime($startDate) <= time() && strtotime($endDate) > time()) {
                $trangThai = 'dang_hoc';
            } elseif (strtotime($endDate) < time()) {
                $trangThai = 'da_hoan_thanh';
            }
            
            $lopHocs[] = [
                'ten' => 'Lớp ' . $khoaHoc->ten . ' - ' . date('m/Y', strtotime($startDate)),
                'ma_lop' => strtoupper(Str::random(2)) . rand(1000, 9999),
                'khoa_hoc_id' => $khoaHocId,
                'giao_vien_id' => $giaoVienId,
                'tro_giang_id' => $troGiangId,
                'hinh_thuc_hoc' => rand(0, 1) ? 'online' : 'offline',
                'lich_hoc' => $lichHocMau[array_rand($lichHocMau)],
                'ngay_bat_dau' => $startDate,
                'ngay_ket_thuc' => $endDate,
                'trang_thai' => $trangThai,
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ];
        }

        DB::table('lop_hocs')->insert($lopHocs);
    }
} 