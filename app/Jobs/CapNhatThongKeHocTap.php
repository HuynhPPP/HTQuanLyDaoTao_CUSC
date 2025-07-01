<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChuongTrinh;
use App\Http\Controllers\ThongKe\ThongKeHocTapController;

class CapNhatThongKeHocTap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $chuongTrinhs = ChuongTrinh::all();
        $controller = new ThongKeHocTapController();

        foreach ($chuongTrinhs as $chuongTrinh) {
            $controller->thongKeTongQuan(
                $chuongTrinh->MaChuongTrinh, 
                date('Y-m')
            );
        }
    }
}
