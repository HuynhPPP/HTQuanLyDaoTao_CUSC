<?php
// config/schedule.php

use App\Jobs\CapNhatThongKeHocTap;
use Illuminate\Support\Facades\Schedule;

protected function schedule(Schedule $schedule)
{
    $schedule->job(new CapNhatThongKeHocTap)
             ->monthlyOn(1, '03:00');
}
