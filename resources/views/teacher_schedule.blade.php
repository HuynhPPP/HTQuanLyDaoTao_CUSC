@extends('layouts.new_app.master')

@section('main-content')
    @php
        use Carbon\Carbon;
        use App\Models\danhsachngaynghi;
        use App\Models\ngaytuhoc;
        use App\Models\GiangDay;
        use App\Models\giaovien;
        // Ngày bắt đầu học
        $startDate = Carbon::parse($schedule->NgayHoc);
        $totalHours = $hocki->TongGioTrienKhai;
        $emptyDays = 0;
        // Xác định ngày đầu tuần (Thứ 2) của tuần chứa ngày bắt đầu học
        $weekStartDate = $startDate->copy()->startOfWeek();
        // Đếm số ngày trống trước ngày bắt đầu học trong tuần đó (không tính thứ 7 và Chủ nhật)
        for ($date = $weekStartDate->copy(); $date->lt($startDate); $date->addDay()) {
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $emptyDays++;
            }
        }
        // Cộng số ngày trống vào tổng thời gian (2 giờ cho mỗi ngày trống)
        $totalHours += $emptyDays * 2;
        // Tính tổng số giờ học và tổng số tuần
        $totalWeeks = ceil($totalHours / 20);
        //lọc môn học có giờ triển khai nhiều hơn 0
        $filteredMonHocs = [];
        $subjectCount = count($monhocs);
        foreach ($monhocs as $index => $monhoc) {
            if ($monhoc && $monhoc->GioTrienKhai > 0) {
                $filteredMonHocs[] = $monhoc;
            }
        }
        // Đếm số môn học
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
        // Các ngày trong tuần
        $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
        // Hàm thêm ngày bỏ qua cuối tuần
        $addDaysSkippingWeekends = function ($date, $days) {
            while ($days > 0) {
                $date->addDay();
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $days--;
                }
            }
            return $date;
        };
        // Xử lý các ngày tự học
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
        // Xử lý các ngày nghỉ
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
        // Hàm lấy thông tin giảng viên cho môn học
        $getTeacherInfo = function ($maMH) use ($schedule) {
            if (!isset($schedule) || !$schedule) {
                return ' [NO SCHEDULE]';
            }
            $giangDay = \App\Models\GiangDay::where('MaMH', $maMH)->where('MaLop', $schedule->MaLop)->first();
            if (!$giangDay) {
                return ' [NO GIANGDAY]';
            }
            $giangVien = \App\Models\giaovien::where('MaGV', $giangDay->MaGV)->first();
            return $giangVien ? ' - GV: ' . $giangVien->HoTenGV : ' [NO GIANGVIEN]';
        };

        // Hàm lấy môn học cho ngày hiện tại
        $getSubjectForDay = function (
            &$subjectOccurrences,
            $currentDate,
            &$totalHours,
            &$examDays,
            &$selfStudyDays,
            $addDaysSkippingWeekends,
            $holidayDates,
            &$examCounter,
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
                            // Xử lý môn học cuối cùng
                            $examDate = $currentDate->copy()->addWeek()->startOfWeek()->next(Carbon::FRIDAY);
                            // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ hoặc ngày tự học
                            while (
                                isset($holidayDates[$examDate->format('Y-m-d')]) ||
                                isset($selfStudyDays[$examDate->format('Y-m-d')])
                            ) {
                                $examDate->addDay();
                            }
                            // Đặt tên là "self-study" cho các ngày trống trước ngày thi
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
                            // Xử lý các môn học khác
                            $examDate = $addDaysSkippingWeekends(clone $currentDate, 5);
                            // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ hoặc ngày tự học
                            while (
                                isset($holidayDates[$examDate->format('Y-m-d')]) ||
                                isset($selfStudyDays[$examDate->format('Y-m-d')])
                            ) {
                                $examDate->addDay();
                            }
                            // Nếu ngày thi vào thứ hai thì không có ngày self-study
                            if ($examDate->dayOfWeek !== Carbon::MONDAY) {
                                $selfStudyDate = $examDate->copy()->subDay();
                                // Nếu ngày self-study không rơi vào thứ 7 hoặc Chủ nhật
                                if (
                                    $selfStudyDate->dayOfWeek !== Carbon::SATURDAY &&
                                    $selfStudyDate->dayOfWeek !== Carbon::SUNDAY &&
                                    !isset($holidayDates[$selfStudyDate->format('Y-m-d')])
                                ) {
                                    if (!isset($selfStudyDays[$selfStudyDate->format('Y-m-d')])) {
                                        $examDays[$selfStudyDate->format('Y-m-d')] = 'self-study';
                                        $totalHours += 2;
                                        // Điều chỉnh các môn học bị trùng với ngày self-study
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
        $examCounter = 0;
        // Tạo lịch học
        $scheduleMatrix = [];
        $examDays = [];
        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStart = $startDate
                ->copy()
                ->addWeeks($week - 1)
                ->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek()->subDays(2);
            $scheduleMatrix[$week] = [];
            foreach ($weekDays as $dayIndex => $day) {
                $currentDate = $weekStart->copy()->addDays($dayIndex);
                $subject = '';
                $style = '';
                if ($currentDate->gte($startDate)) {
                    if (isset($examDays[$currentDate->format('Y-m-d')])) {
                        $subject = $examDays[$currentDate->format('Y-m-d')];
                        $style = 'color: blue; font-weight: bold; filter-bg-exam';
                        if ($subject === 'self-study') {
                            $style = 'text-dark filter-bg-self-study';
                        }
                    } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                        $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                        $style = 'color: green; font-weight: bold; filter-bg-self-study';
                    } else {
                        if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                            $subject = $holidayDates[$currentDate->format('Y-m-d')];
                            $style = 'background-color: yellow; filter-bg-holiday';
                        } else {
                            $subject = $getSubjectForDay(
                                $subjectOccurrences,
                                $currentDate,
                                $totalHours,
                                $examDays,
                                $selfStudyDays,
                                $addDaysSkippingWeekends,
                                $holidayDates,
                                $examCounter,
                            );
                            if ($subject) {
                                if ($subjectOccurrences[$subject]['first'] == $currentDate) {
                                    $style = 'color: red; font-weight: bold;';
                                } elseif ($subjectOccurrences[$subject]['last'] == $currentDate) {
                                    $style = 'color: purple; font-weight: bold;';
                                }
                            }
                        }
                    }
                } else {
                    // Ngày trước ngày bắt đầu học
                    $subject = '';
                    $style = '';
                }
                $totalWeeks = ceil($totalHours / 20);
                $scheduleMatrix[$week][$day] = [
                    'date' => $currentDate->format('d/m/Y'),
                    'subject' => $subject,
                    'style' => $style,
                ];
            }
        }
    @endphp
    <section class="section">
        <div class="section-header">
            <h1>Lịch Giảng Dạy - {{ $schedule->TenTKB }}</h1>
        </div>
        <div class="row justify-content-center my-5">
            <div class="col-lg-10">
                <div class="card shadow rounded-4 border-0 mb-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo_CTU.png') }}" alt="logo" width="80" class="mb-3">
                            <h5 class="fw-bold text-primary mb-1">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
                            <h1 class="fw-bold mb-2">CANTHO UNIVERSITY SOFTWARE CENTER</h1>
                            <p class="text-secondary mb-0">Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel:
                                0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
                        </div>
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">
                                LỊCH GIẢNG DẠY LỚP {{ $schedule->MaLop }} - {{ $chuongTrinhName }}<br>
                                HỌC KỲ {{ $hocki->TenHK }}: {{ $chuongTrinhName }} (CPIDA)
                            </h2>
                        </div>
                        <div class="row justify-content-between mb-4">
                            <div class="col-md-6 text-start">
                                <p class="mb-1"><strong>Thời gian:</strong> <span
                                        style="color: red;">{{ $dsmh && $dsmh->khungGio ? $dsmh->khungGio->ThoiGian : $dsmh->TenKhungGio ?? 'Chưa có' }}</span>
                                </p>
                                <p class="mb-1"><strong>Mã lớp:</strong> <span
                                        style="color: red;">{{ $schedule->MaLop }}</span></p>
                                <p class="mb-1"><strong>Ver:</strong> {{ $chuongtrinh->PhienBan ?? 'N/A' }}
                                    &nbsp;&nbsp;&nbsp;
                                    {{ $chuongtrinh && $chuongtrinh->NgayTrienKhaiPB ? \Carbon\Carbon::parse($chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6 text-start">
                                <p class="mb-1"><strong>Bắt đầu học từ ngày:</strong>
                                    <span
                                        style="color: red;">{{ \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y') }}</span>
                                </p>
                                <p class="mb-1"><strong>Học Lý thuyết tại phòng:</strong>
                                    <span style="color: red;">{{ $phonglt->TenPhong ?? 'Chưa có' }}</span>
                                </p>
                                <p class="mb-1"><strong>Học Thực hành tại phòng:</strong>
                                    <span style="color: red;">{{ $phongth->TenPhong ?? ' Chưa có ' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover rounded shadow-sm bg-white">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">NGÀY</th>
                                        <th class="text-center">TUẦN</th>
                                        <th class="text-center">THỨ HAI</th>
                                        <th class="text-center">THỨ BA</th>
                                        <th class="text-center">THỨ TƯ</th>
                                        <th class="text-center">THỨ NĂM</th>
                                        <th class="text-center">THỨ SÁU</th>
                                        <th class="text-center">THỨ BẢY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scheduleMatrix as $week => $days)
                                        @php
                                            $weekDates = collect($days)->pluck('date')->toArray();
                                        @endphp
                                        <tr>
                                            <td class="text-wrap align-middle text-center" style="width: 12rem;">
                                                {{ implode(' - ', [reset($weekDates), end($weekDates)]) }}</td>
                                            <td class="text-wrap align-middle text-center">{{ $week }}</td>
                                            @foreach ($days as $dayData)
                                                <td class="text-wrap align-middle text-center"
                                                    style="width: 12rem; {{ $dayData['style'] }}">
                                                    @if ($dayData['subject'])
                                                        @if (isset($subjectOccurrences[$dayData['subject']]))
                                                            {{ $subjectOccurrences[$dayData['subject']]['TenMH'] }}{!! $getTeacherInfo($dayData['subject']) !!}
                                                        @else
                                                            {{ $dayData['subject'] }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                            <a href="{{ route('exportTeacherSchedule', $schedule->TenTKB) }}" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    /* CSS cho các nút */
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        margin: 3px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 100px;
        height: 38px;
        line-height: 1;
    }

    .btn i {
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #6c5ce7;
        border-color: #6c5ce7;
    }

    .btn-primary:hover {
        background-color: #5f3dc4;
        border-color: #5f3dc4;
    }

    /* CSS cho bảng */
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background-color: #4e73df;
        color: white;
        font-weight: 500;
        border: none;
        padding: 12px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* CSS cho card */
    .card {
        border-radius: 8px;
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 1.5rem;
    }

    .fc-event {
        cursor: pointer;
        padding: 2px 5px;
    }

    .event-start {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .event-end {
        background-color: #6f42c1 !important;
        border-color: #6f42c1 !important;
    }

    .event-exam {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }

    .event-self-study {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }

    .event-holiday {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
</style>
