<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaiLieuBoTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách ID lớp học
        $lopHocIds = DB::table('lop_hocs')->pluck('id')->toArray();
        
        // Lấy danh sách ID của các bài học để liên kết
        $baiHocIds = DB::table('bai_hocs')->pluck('id')->toArray();
        
        // Danh sách các loại tài liệu mẫu
        $loaiTaiLieu = [
            'pdf' => ['Slide bài giảng', 'Tài liệu tham khảo', 'Đề thi mẫu', 'Ngữ pháp', 'Từ vựng'],
            'docx' => ['Bài tập', 'Hướng dẫn học', 'Tài liệu bổ sung'],
            'xlsx' => ['Bảng điểm', 'Thống kê từ vựng', 'Lịch học'],
            'mp3' => ['Bài nghe', 'Phát âm', 'Hội thoại'],
            'mp4' => ['Video bài giảng', 'Hướng dẫn phát âm', 'Giao tiếp thực tế']
        ];
        
        // Tạo tài liệu cho mỗi lớp học
        foreach ($lopHocIds as $lopHocId) {
            // Lấy thông tin lớp học
            $lopHoc = DB::table('lop_hocs')->where('id', $lopHocId)->first();
            
            // Lấy ID khóa học của lớp
            $khoaHocId = $lopHoc->khoa_hoc_id;
            
            // Lấy thông tin khóa học
            $khoaHoc = DB::table('khoa_hocs')->where('id', $khoaHocId)->first();
            
            // Số lượng tài liệu mỗi lớp từ 3-10 tài liệu
            $soLuongTaiLieu = rand(3, 10);
            
            $taiLieus = [];
            
            for ($i = 0; $i < $soLuongTaiLieu; $i++) {
                // Chọn ngẫu nhiên loại tài liệu
                $extension = array_rand($loaiTaiLieu);
                $nameOptions = $loaiTaiLieu[$extension];
                $nameBase = $nameOptions[array_rand($nameOptions)];
                
                // Tạo tên tài liệu kết hợp với tên khóa học hoặc bài học
                $name = $nameBase . ' - ' . $khoaHoc->ten;
                
                // Chọn ngẫu nhiên một bài học để liên kết
                $baiHocId = $baiHocIds[array_rand($baiHocIds)];
                
                // Tạo tên file giả
                $fileName = Str::slug($name) . '-' . rand(1000, 9999) . '.' . strtolower($extension);
                
                $taiLieus[] = [
                    'bai_hoc_id' => $baiHocId,
                    'lop_hoc_id' => $lopHocId,
                    'tieu_de' => $name,
                    'mo_ta' => 'Tài liệu bổ trợ cho lớp học ' . $lopHoc->ten,
                    'duong_dan_file' => 'storage/tai-lieu/' . $fileName,
                    'tao_luc' => now(),
                    'cap_nhat_luc' => now(),
                ];
            }
            
            DB::table('tai_lieu_bo_tros')->insert($taiLieus);
        }
    }
} 