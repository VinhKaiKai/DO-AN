<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách ID khóa học
        $khoaHocIds = DB::table('khoa_hocs')->pluck('id')->toArray();
        
        foreach ($khoaHocIds as $khoaHocId) {
            // Lấy thông tin khóa học
            $khoaHoc = DB::table('khoa_hocs')->where('id', $khoaHocId)->first();
            
            // Tạo số bài học theo tổng số bài trong khóa học
            $soBai = $khoaHoc->tong_so_bai;
            
            // Tạo danh sách bài học theo từng khóa học
            $baiHocs = [];
            
            // Nội dung bài học cho khóa học N5
            if (strpos($khoaHoc->ten, 'N5') !== false) {
                $titles = [
                    'Giới thiệu về ngôn ngữ và văn hóa Trung',
                    'Bảng chữ cái Hiragana và Katakana',
                    'Cách chào hỏi và giới thiệu bản thân',
                    'Số đếm và cách đọc số trong tiếng Trung',
                    'Từ vựng về gia đình và các mối quan hệ',
                    'Cấu trúc câu cơ bản trong tiếng Trung',
                    'Từ vựng về thời gian và ngày tháng',
                    'Từ vựng về màu sắc và hình dạng',
                    'Từ vựng về thức ăn và đồ uống',
                    'Động từ nhóm 1 và cách chia động từ',
                    'Động từ nhóm 2 và cách chia động từ',
                    'Động từ nhóm 3 và cách chia động từ',
                    'Tính từ đuôi い và な và cách sử dụng',
                    'Từ vựng về giao thông và phương tiện đi lại',
                    'Cách sử dụng trợ từ trong tiếng Trung',
                    'Từ vựng về địa điểm và phương hướng',
                    'Cách diễn đạt sở thích và ý kiến cá nhân',
                    'Từ vựng về thời tiết và mùa',
                    'Cách diễn đạt mời và đề nghị',
                    'Cách diễn đạt cảm xúc và cảm giác',
                    'Luyện nghe và hội thoại cơ bản',
                    'Đọc hiểu văn bản đơn giản',
                    'Luyện viết chữ Kanji cơ bản',
                    'Luyện nói theo tình huống hàng ngày',
                    'Ôn tập và chuẩn bị thi N5',
                ];
                
                for ($i = 0; $i < $soBai; $i++) {
                    $baiHocs[] = [
                        'khoa_hoc_id' => $khoaHocId,
                        'tieu_de' => $titles[$i] ?? 'Bài ' . ($i + 1) . ' - Khóa học N5',
                        'mo_ta' => 'Nội dung bài học ' . ($i + 1) . ' của khóa học N5, giúp học viên làm quen với các kiến thức cơ bản.',
                        'so_thu_tu' => $i + 1,
                        'tao_luc' => now(),
                        'cap_nhat_luc' => now(),
                    ];
                }
            } 
            // Nội dung bài học cho khóa học N4
            elseif (strpos($khoaHoc->ten, 'N4') !== false) {
                $titles = [
                    'Ôn tập kiến thức N5 và giới thiệu N4',
                    'Mở rộng từ vựng về chủ đề công việc',
                    'Mở rộng từ vựng về chủ đề trường học',
                    'Mở rộng từ vựng về chủ đề sức khỏe',
                    'Cấu trúc câu phức tạp hơn trong tiếng Trung',
                    'Cách biểu thị nguyên nhân và kết quả',
                    'Cách biểu thị giả định và điều kiện',
                    'Cách sử dụng trợ từ phức tạp hơn',
                    'Cách biểu thị so sánh trong tiếng Trung',
                    'Cách biểu thị sự cho phép và cấm đoán',
                    'Cách diễn đạt khả năng và kỹ năng',
                    'Từ vựng về hoạt động hàng ngày',
                    'Từ vựng về sở thích và giải trí',
                    'Đọc hiểu các văn bản ngắn',
                    'Luyện nghe các hội thoại hằng ngày',
                    'Cách biểu thị ý định và dự định',
                    'Cách biểu thị lịch sử và trải nghiệm',
                    'Từ vựng về văn hóa và phong tục Trung Bản',
                    'Cách sử dụng các biểu hiện lịch sự',
                    'Cách diễn đạt sự thay đổi trạng thái',
                    'Cách biểu thị sự tiếp tục và liên tục',
                    'Mở rộng từ vựng về quan hệ xã hội',
                    'Luyện viết đoạn văn ngắn',
                    'Luyện nói theo tình huống phức tạp hơn',
                    'Luyện nghe phân biệt các âm tương tự',
                    'Học thêm các chữ Kanji N4',
                    'Ôn tập ngữ pháp N4',
                    'Luyện đọc hiểu và trả lời câu hỏi',
                    'Luyện nghe và tóm tắt nội dung',
                    'Ôn tập và chuẩn bị thi N4',
                ];
                
                for ($i = 0; $i < $soBai; $i++) {
                    $baiHocs[] = [
                        'khoa_hoc_id' => $khoaHocId,
                        'tieu_de' => $titles[$i] ?? 'Bài ' . ($i + 1) . ' - Khóa học N4',
                        'mo_ta' => 'Nội dung bài học ' . ($i + 1) . ' của khóa học N4, giúp học viên nâng cao kiến thức và kỹ năng giao tiếp.',
                        'so_thu_tu' => $i + 1,
                        'tao_luc' => now(),
                        'cap_nhat_luc' => now(),
                    ];
                }
            }
            // Nội dung bài học cho các khóa học khác
            else {
                for ($i = 0; $i < $soBai; $i++) {
                    $baiHocs[] = [
                        'khoa_hoc_id' => $khoaHocId,
                        'tieu_de' => 'Bài ' . ($i + 1) . ' - ' . $khoaHoc->ten,
                        'mo_ta' => 'Nội dung bài học ' . ($i + 1) . ' của khóa học ' . $khoaHoc->ten,
                        'so_thu_tu' => $i + 1,
                        'tao_luc' => now(),
                        'cap_nhat_luc' => now(),
                    ];
                }
            }
            
            // Thêm bài học vào database
            DB::table('bai_hocs')->insert($baiHocs);
            
            // Gán bài học vào lớp học
            $lopHocIds = DB::table('lop_hocs')->where('khoa_hoc_id', $khoaHocId)->pluck('id')->toArray();
            
            // Thêm bài học vào mỗi lớp học thuộc khóa học
            if (count($lopHocIds) > 0) {
                $baiHocIds = DB::table('bai_hocs')->where('khoa_hoc_id', $khoaHocId)->orderBy('so_thu_tu')->pluck('id')->toArray();
                
                foreach ($lopHocIds as $lopHocId) {
                    $baiHocLops = [];
                    
                    foreach ($baiHocIds as $index => $baiHocId) {
                        $baiHocLops[] = [
                            'lop_hoc_id' => $lopHocId,
                            'bai_hoc_id' => $baiHocId,
                            'so_thu_tu' => $index + 1,
                            'ngay_bat_dau' => date('Y-m-d', strtotime('+ ' . ($index * 2) . ' days', strtotime(DB::table('lop_hocs')->where('id', $lopHocId)->value('ngay_bat_dau')))),
                            'tao_luc' => now(),
                            'cap_nhat_luc' => now(),
                        ];
                    }
                    
                    DB::table('bai_hoc_lops')->insert($baiHocLops);
                }
            }
        }
    }
} 