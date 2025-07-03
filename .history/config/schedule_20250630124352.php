<?php
// config/schedule.php

use App\Jobs\CapNhatThongKeHocTap;
use Illuminate\Support\Facades\Schedule;

return function (Schedule $schedule) {
    // Chạy job cập nhật thống kê học tập vào ngày đầu tiên của mỗi tháng lúc 3 giờ sáng
    $schedule->job(new CapNhatThongKeHocTap)
             ->monthlyOn(1, '03:00');
};