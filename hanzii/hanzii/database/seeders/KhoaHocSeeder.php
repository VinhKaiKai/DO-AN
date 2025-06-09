<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhoaHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $khoaHocs = [
            [
                'ten' => 'Tiếng Trung cho người mới bắt đầu (N5)',
                'mo_ta' => 'Khóa học dành cho người mới bắt đầu học tiếng Trung, giúp bạn nắm vững ngữ pháp cơ bản và các kỹ năng giao tiếp đơn giản.',
                'hoc_phi' => 3000000.00,
                'tong_so_bai' => 25,
                'thoi_gian_hoan_thanh' => '3 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung trung cấp (N4)',
                'mo_ta' => 'Khóa học tiếp nối N5, giúp học viên nâng cao kỹ năng nghe, nói, đọc, viết tiếng Trung ở trình độ trung cấp.',
                'hoc_phi' => 3500000.00,
                'tong_so_bai' => 30,
                'thoi_gian_hoan_thanh' => '4 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung trung cấp nâng cao (N3)',
                'mo_ta' => 'Khóa học giúp học viên có thể hiểu được tiếng Trung được sử dụng trong các tình huống đời thường.',
                'hoc_phi' => 4000000.00,
                'tong_so_bai' => 30,
                'thoi_gian_hoan_thanh' => '4 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung nâng cao (N2)',
                'mo_ta' => 'Khóa học nâng cao giúp học viên có khả năng đọc hiểu và nghe hiểu tiếng Trung ở mức độ gần tương đương với người bản xứ.',
                'hoc_phi' => 4500000.00,
                'tong_so_bai' => 35,
                'thoi_gian_hoan_thanh' => '5 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung thành thạo (N1)',
                'mo_ta' => 'Khóa học dành cho những học viên muốn đạt trình độ tiếng Trung cao nhất, tương đương với người bản xứ.',
                'hoc_phi' => 5000000.00,
                'tong_so_bai' => 40,
                'thoi_gian_hoan_thanh' => '6 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung giao tiếp cơ bản',
                'mo_ta' => 'Khóa học ngắn dành cho người muốn học giao tiếp tiếng Trung cơ bản cho du lịch hoặc làm việc.',
                'hoc_phi' => 2000000.00,
                'tong_so_bai' => 15,
                'thoi_gian_hoan_thanh' => '2 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
            [
                'ten' => 'Tiếng Trung thương mại',
                'mo_ta' => 'Khóa học chuyên sâu về tiếng Trung trong môi trường kinh doanh và thương mại.',
                'hoc_phi' => 4200000.00,
                'tong_so_bai' => 20,
                'thoi_gian_hoan_thanh' => '3 tháng',
                'tao_luc' => now(),
                'cap_nhat_luc' => now(),
            ],
        ];

        DB::table('khoa_hocs')->insert($khoaHocs);
    }
} 