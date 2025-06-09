<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BinhLuan;
use Illuminate\Support\Facades\DB;

class CapNhatTrangThaiDaPhanHoi extends Command
{
    /**
     * Tên lệnh console
     *
     * @var string
     */
    protected $signature = 'binh-luan:cap-nhat-trang-thai';

    /**
     * Mô tả lệnh
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái da_phan_hoi cho các bình luận đã có phản hồi nhưng chưa được đánh dấu';

    /**
     * Khởi tạo lệnh
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Thực thi lệnh
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật trạng thái phản hồi bình luận...');

        // Tìm tất cả bình luận đã có phản hồi nhưng chưa được đánh dấu
        $binhLuanIds = DB::table('binh_luans as bl')
            ->join('binh_luans as ph', 'bl.id', '=', 'ph.binh_luan_goc_id')
            ->where('bl.da_phan_hoi', false)
            ->select('bl.id')
            ->distinct()
            ->get()
            ->pluck('id')
            ->toArray();

        if (empty($binhLuanIds)) {
            $this->info('Không có bình luận nào cần cập nhật.');
            return 0;
        }

        // Cập nhật trạng thái
        $count = BinhLuan::whereIn('id', $binhLuanIds)
            ->update(['da_phan_hoi' => true]);

        $this->info("Đã cập nhật {$count} bình luận.");
        return 0;
    }
} 