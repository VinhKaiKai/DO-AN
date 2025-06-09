<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DangKyHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách ID lớp học
        $lopHocIds = DB::table('lop_hocs')->pluck('id')->toArray();
        
        // Lấy danh sách ID học viên
        $hocVienIds = DB::table('hoc_viens')->pluck('id')->toArray();
        
        // Chắc chắn có đủ học viên
        if (empty($hocVienIds)) {
            echo "Không có học viên nào trong hệ thống. Vui lòng chạy NguoiDungSeeder trước.\n";
            return;
        }
        
        // Đăng ký học cho mỗi lớp
        foreach ($lopHocIds as $lopHocId) {
            // Lấy thông tin lớp học
            $lopHoc = DB::table('lop_hocs')->where('id', $lopHocId)->first();
            
            // Số lượng học viên mỗi lớp từ 3-5 học viên để tránh lỗi
            $soLuongHocVien = min(count($hocVienIds), rand(3, 5));
            
            // Lấy ngẫu nhiên một số học viên để đăng ký
            $selectedHocVienIds = array_rand(array_flip($hocVienIds), $soLuongHocVien);
            if (!is_array($selectedHocVienIds)) {
                $selectedHocVienIds = [$selectedHocVienIds];
            }
            
            $dangKyHocs = [];
            
            foreach ($selectedHocVienIds as $hocVienId) {
                // Ngày đăng ký từ 1 tháng trước ngày bắt đầu lớp đến ngày hiện tại
                $ngayDangKy = date('Y-m-d', rand(
                    strtotime('-1 month', strtotime($lopHoc->ngay_bat_dau)),
                    min(time(), strtotime($lopHoc->ngay_bat_dau))
                ));
                
                // Trạng thái đăng ký
                $trangThaiOptions = ['cho_xac_nhan', 'da_xac_nhan'];
                $trangThai = $trangThaiOptions[array_rand($trangThaiOptions)];
                
                // Nếu lớp đã bắt đầu, ưu tiên trạng thái đã xác nhận
                if (strtotime($lopHoc->ngay_bat_dau) <= time()) {
                    $trangThai = 'da_xac_nhan';
                }
                
                $dangKyHocs[] = [
                    'hoc_vien_id' => $hocVienId,
                    'lop_hoc_id' => $lopHocId,
                    'ngay_dang_ky' => $ngayDangKy,
                    'trang_thai' => $trangThai,
                    'tao_luc' => now(),
                    'cap_nhat_luc' => now(),
                ];
            }
            
            if (count($dangKyHocs) > 0) {
                DB::table('dang_ky_hocs')->insert($dangKyHocs);
            }
        }
    }
} 