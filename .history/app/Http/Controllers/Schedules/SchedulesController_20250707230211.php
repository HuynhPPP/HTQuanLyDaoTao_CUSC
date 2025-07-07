<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;
use App\Models\khoadaotao;
use App\Models\tkb;
use App\Models\phonghoc;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function CreateSchedules()
    {
        if (session()->has('user')) {
            $data = [
                'khoadaotaos' => khoadaotao::all(),
                'tkbs' => tkb::all(),
                'phonghocs' => phonghoc::all(),
            ];
            return view('schedules.admin.schedules', $data);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
}
