<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CalendarController extends Controller
{
    public function getEvents()
    {
        $events = DB::table('giangday')
            ->join('lophoc', 'giangday.MaLop', '=', 'lophoc.MaLop')
            ->join('chuongtrin_hmonhoc', 'giangday.MaChuongTrinh', '=', 'chuongtrin_hmonhoc.MaChuongTrinh')
            ->select(
                'lophoc.TenLop as title',
                'giangday.NgayBatDau as start',
                'giangday.NgayKetThuc as end',
                'chuongtrin_hmonhoc.TenChuongTrinh as description'
            )
            ->get()
            ->map(function ($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->start,
                    'end' => $event->end,
                    'description' => $event->description,
                    'backgroundColor' => $this->getRandomColor(),
                    'borderColor' => $this->getRandomColor(),
                    'textColor' => '#fff'
                ];
            });

        return response()->json($events);
    }

    private function getRandomColor()
    {
        $colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'];
        return $colors[array_rand($colors)];
    }
}
