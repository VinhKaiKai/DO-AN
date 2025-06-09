<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NguoiDung;
use App\Models\LienHe;
use App\Notifications\LienHeNotification;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi thông báo test về liên hệ mới';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return 1;
    }
}
