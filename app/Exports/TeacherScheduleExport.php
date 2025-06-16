<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;
use App\Models\GiangDay;
use App\Models\giaovien;

class TeacherScheduleExport implements FromView, WithTitle
{
    protected $schedule;
    protected $giangDays;
    protected $ngaynghis;
    protected $ngaytuhocs;
    protected $hocki;
    protected $chuongTrinhName;
    protected $phonglt;
    protected $phongth;
    protected $monhocs;

    public function __construct($schedule, $giangDays, $ngaynghis, $ngaytuhocs, $hocki, $chuongTrinhName, $phonglt, $phongth, $monhocs)
    {
        $this->schedule = $schedule;
        $this->giangDays = $giangDays;
        $this->ngaynghis = $ngaynghis;
        $this->ngaytuhocs = $ngaytuhocs;
        $this->hocki = $hocki;
        $this->chuongTrinhName = $chuongTrinhName;
        $this->phonglt = $phonglt;
        $this->phongth = $phongth;
        $this->monhocs = $monhocs;
    }

    public function title(): string
    {
        \Log::debug('Sheet Title Debug: ' . 'LichGiangVien');
        return 'LichGiangVien';
    }

    public function view(): View
    {
        $schedule = $this->schedule;
        $giangDays = $this->giangDays;
        $ngaynghis = $this->ngaynghis;
        $ngaytuhocs = $this->ngaytuhocs;
        $hocki = $this->hocki;
        $chuongTrinhName = $this->chuongTrinhName;
        $phonglt = $this->phonglt;
        $phongth = $this->phongth;
        $monhocs = $this->monhocs;

        // Logic tính toán tương tự như trong teacher_schedule.blade.php
        $startDate = Carbon::parse($schedule->NgayHoc);
        $totalHours = $hocki->TongGioTrienKhai;
        $emptyDays = 0;
        $weekStartDate = $startDate->copy()->startOfWeek();
        for ($date = $weekStartDate->copy(); $date->lt($startDate); $date->addDay()) {
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $emptyDays++;
            }
        }
        $totalHours += $emptyDays * 2;
        $totalWeeks = ceil($totalHours / 20);
        $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
        
        $selfStudyDays = [];
        foreach ($ngaytuhocs as $ngaytuhoc) {
            $selfStudyStart = Carbon::parse($ngaytuhoc->NgayBDTuHoc);
            $selfStudyEnd = Carbon::parse($ngaytuhoc->NgayKTTuHoc);
            while ($selfStudyStart->lte($selfStudyEnd)) {
                if ($selfStudyStart->dayOfWeek !== Carbon::SATURDAY && $selfStudyStart->dayOfWeek !== Carbon::SUNDAY) {
                    $selfStudyDays[$selfStudyStart->format('Y-m-d')] = $ngaytuhoc->TenNgayTuHoc;
                    $totalHours += 2;
                }
                $selfStudyStart->addDay();
            }
        }

        $holidayDates = [];
        foreach ($ngaynghis as $ngaynghi) {
            $holidayStart = Carbon::parse($ngaynghi->NgayBDNghi);
            $holidayEnd = Carbon::parse($ngaynghi->NgayKT);
            while ($holidayStart->lte($holidayEnd)) {
                if ($holidayStart->dayOfWeek !== Carbon::SATURDAY && $holidayStart->dayOfWeek !== Carbon::SUNDAY) {
                    $holidayDates[$holidayStart->format('Y-m-d')] = $ngaynghi->TenNgayNghi;
                    $totalHours += 2;
                }
                $holidayStart->addDay();
            }
        }

        $filteredMonHocs = [];
        foreach ($monhocs as $monhoc) {
            if ($monhoc && $monhoc->GioTrienKhai > 0) {
                $filteredMonHocs[] = $monhoc;
            }
        }

        $subjectOccurrences = [];
        $subjectCount = count($filteredMonHocs);
        foreach ($filteredMonHocs as $index => $monhoc) {
            $subjectOccurrences[$monhoc->MaMH] = [
                'TenMH' => $monhoc->TenMH,
                'first' => null,
                'last' => null,
                'remaining' => $monhoc->GioTrienKhai,
            ];
            if ($index === $subjectCount - 1) {
                $subjectOccurrences[$monhoc->MaMH]['lastSubject'] = true;
            }
        }

        $addDaysSkippingWeekends = function ($date, $days) {
            while ($days > 0) {
                $date->addDay();
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $days--;
                }
            }
            return $date;
        };

        $getTeacherInfo = function ($maMH) use ($schedule) {
            if (!isset($schedule) || !$schedule) {
                return ' [NO SCHEDULE]';
            }
            $giangDay = GiangDay::where('MaMH', $maMH)->where('MaLop', $schedule->MaLop)->first();
            if (!$giangDay) {
                return ' [NO GIANGDAY]';
            }
            $giangVien = giaovien::where('MaGV', $giangDay->MaGV)->first();
            return $giangVien ? ' - GV: ' . $giangVien->HoTenGV : ' [NO GIANGVIEN]';
        };

        $examCounter = 0;
        $examDays = [];

        $getSubjectForDay = function (
            &$subjectOccurrences,
            $currentDate,
            &$totalHours,
            &$examDays,
            &$selfStudyDays,
            $addDaysSkippingWeekends,
            $holidayDates,
            &$examCounter
        ) use ($getTeacherInfo) {
            foreach ($subjectOccurrences as $subject => &$details) {
                if ($details['remaining'] > 0) {
                    if (is_null($details['first'])) {
                        $details['first'] = $currentDate;
                    }
                    $details['remaining'] -= 4;
                    if ($details['remaining'] <= 0) {
                        $details['last'] = $currentDate;
                        if (isset($details['lastSubject']) && $details['lastSubject']) {
                            $examDate = $currentDate->copy()->addWeek()->startOfWeek()->next(Carbon::FRIDAY);
                            while (
                                isset($holidayDates[$examDate->format('Y-m-d')]) ||
                                isset($selfStudyDays[$examDate->format('Y-m-d')])
                            ) {
                                $examDate->addDay();
                            }
                            $emptyDays = $currentDate->diffInDays($examDate) - 1;
                            for ($i = 0; $i < $emptyDays; $i++) {
                                $selfStudyDate = $currentDate->copy()->addDays($i + 1);
                                if (
                                    $selfStudyDate->dayOfWeek !== Carbon::SATURDAY &&
                                    $selfStudyDate->dayOfWeek !== Carbon::SUNDAY &&
                                    !isset($holidayDates[$selfStudyDate->format('Y-m-d')])
                                ) {
                                    if (!isset($selfStudyDays[$selfStudyDate->format('Y-m-d')])) {
                                        $examDays[$selfStudyDate->format('Y-m-d')] = 'self-study';
                                        $totalHours += 2;
                                    }
                                }
                            }
                        } else {
                            $examDate = $addDaysSkippingWeekends(clone $currentDate, 5);
                            while (
                                isset($holidayDates[$examDate->format('Y-m-d')]) ||
                                isset($selfStudyDays[$examDate->format('Y-m-d')])
                            ) {
                                $examDate->addDay();
                            }
                            if ($examDate->dayOfWeek !== Carbon::MONDAY) {
                                $selfStudyDate = $examDate->copy()->subDay();
                                if (
                                    $selfStudyDate->dayOfWeek !== Carbon::SATURDAY &&
                                    $selfStudyDate->dayOfWeek !== Carbon::SUNDAY &&
                                    !isset($holidayDates[$selfStudyDate->format('Y-m-d')])
                                ) {
                                    if (!isset($selfStudyDays[$selfStudyDate->format('Y-m-d')])) {
                                        $examDays[$selfStudyDate->format('Y-m-d')] = 'self-study';
                                        $totalHours += 2;
                                        foreach ($subjectOccurrences as $s => &$d) {
                                            if ($d['first'] && $d['first']->eq($selfStudyDate)) {
                                                $d['first'] = $addDaysSkippingWeekends($selfStudyDate->copy(), 1);
                                                $d['last'] = $addDaysSkippingWeekends($d['last']->copy(), 1);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $examCounter++;
                        $examDays[$examDate->format('Y-m-d')] =
                            'Thi ' . $subjectOccurrences[$subject]['TenMH'] . " ($subject, E$examCounter) - L";
                        $totalHours += 2;
                    }
                    return $subject;
                }
            }
            return '';
        };

        $tempScheduleMatrix = [];
        $tempExamCounter = 0;
        $tempSelfStudyDays = $selfStudyDays;
        $tempHolidayDates = $holidayDates;
        $tempTotalHours = $totalHours;

        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
            foreach ($weekDays as $dayIndex => $day) {
                $currentDate = $weekStart->copy()->addDays($dayIndex);

                if ($currentDate->gte($startDate)) {
                    if (isset($tempHolidayDates[$currentDate->format('Y-m-d')])) {
                    } elseif (isset($tempSelfStudyDays[$currentDate->format('Y-m-d')])) {
                    } else {
                        $getSubjectForDay(
                            $tempSubjectOccurrences,
                            $currentDate,
                            $tempTotalHours,
                            $examDays,
                            $tempSelfStudyDays,
                            $addDaysSkippingWeekends,
                            $tempHolidayDates,
                            $tempExamCounter
                        );
                    }
                }
            }
        }
        $totalWeeks = ceil($tempTotalHours / 20);

        $teacherSchedules = [];
        foreach ($giangDays as $maGV => $subjects) {
            $teacherSchedules[$maGV] = [
                'info' => $subjects->first()->giaovien,
                'schedule' => []
            ];

            for ($week = 1; $week <= $totalWeeks; $week++) {
                $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek()->subDays(2);
                $teacherSchedules[$maGV]['schedule'][$week] = [];

                foreach ($weekDays as $dayIndex => $day) {
                    $currentDate = $weekStart->copy()->addDays($dayIndex);
                    $scheduleInfo = [
                        'date' => $currentDate->format('d/m/Y'),
                        'subject' => '',
                        'style' => ''
                    ];

                    if ($currentDate->gte($startDate)) {
                        if (isset($examDays[$currentDate->format('Y-m-d')])) {
                            $scheduleInfo['subject'] = $examDays[$currentDate->format('Y-m-d')];
                            $scheduleInfo['style'] = 'font-weight: bold; background-color: #bbdefb;';
                        } elseif (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                            $scheduleInfo['subject'] = $holidayDates[$currentDate->format('Y-m-d')];
                            $scheduleInfo['style'] = 'background-color: yellow;';
                        } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                            $scheduleInfo['subject'] = $selfStudyDays[$currentDate->format('Y-m-d')];
                            $scheduleInfo['style'] = 'background-color: lightgreen;';
                        } else {
                            foreach ($subjects as $subject) {
                                if ($subject->monhoc && $subject->monhoc->GioTrienKhai > 0) {
                                    $teacherFullName = '';
                                    if ($subject->giaovien) {
                                        $teacherFullName = ' - GV: ' . $subject->giaovien->HoTenGV;
                                    }
                                    $scheduleInfo['subject'] = $subject->monhoc->TenMH . $teacherFullName;
                                    $scheduleInfo['style'] = 'Có lịch dạy';
                                    break;
                                }
                            }
                        }
                    }

                    $teacherSchedules[$maGV]['schedule'][$week][$day] = $scheduleInfo;
                }
            }
        }

        return view('exports.teacher_schedule_export', compact(
            'schedule',
            'teacherSchedules',
            'hocki',
            'chuongTrinhName',
            'phonglt',
            'phongth',
            'startDate',
            'totalWeeks',
            'weekDays'
        ));
    }
} 