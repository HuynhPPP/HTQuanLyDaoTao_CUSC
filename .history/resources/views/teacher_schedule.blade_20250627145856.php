@extends('layouts.new_app.master')

@section('main-content')
    @php
        use Carbon\Carbon;
        use App\Models\danhsachngaynghi;
        use App\Models\ngaytuhoc;
        use App\Models\GiangDay;
        use App\Models\giaovien;
        use App\Models\danhsachmonhoc;
        use App\Models\ngaynghi;

        // Pre-load all holiday and self-study names
        $allHolidayNames = ngaynghi::pluck('TenNgayNghi')->toArray();

        // Các ngày trong tuần đầy đủ để render bảng
        $weekDaysFull = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];

        // Replicate getTeacherInfo function
        $getTeacherInfo = function ($maMH) use ($schedule) {
            if (!isset($schedule) || !$schedule) {
                return '';
            }
            $giangDay = \App\Models\GiangDay::where('MaMH', $maMH)->where('MaLop', $schedule->MaLop)->first();
            if (!$giangDay) {
                return '';
            }
            $giangVien = \App\Models\giaovien::where('MaGV', $giangDay->MaGV)->first();
            return $giangVien ? ' - GV: ' . $giangVien->HoTenGV : '';
        };

        // Build subjectOccurrences for TenMH lookups
        $subjectOccurrences = [];
        foreach ($scheduleMatrix as $weekData) {
            foreach ($weekData as $dayName => $dayInfo) {
                $subjectCode = $dayInfo['subject'];
                // Skip if it's an exam, self-study, or holiday
        if (
            strpos($subjectCode, 'Thi') === 0 ||
            $subjectCode === 'self-study' ||
            in_array($subjectCode, $allHolidayNames)
        ) {
            continue;
        }
        // If it's a valid MaMH and not already in subjectOccurrences
                if (!empty($subjectCode) && !isset($subjectOccurrences[$subjectCode])) {
                    $monHoc = danhsachmonhoc::where('MaMH', $subjectCode)->first();
                    if ($monHoc) {
                        $subjectOccurrences[$subjectCode] = [
                            'TenMH' => $monHoc->TenMH,
                            'GioTrienKhai' => $monHoc->GioTrienKhai,
                        ];
                    }
                }
            }
        }

        // Hàm tìm ngày thi hợp lý: đúng thứ của ngày kết thúc môn, tuần kế tiếp, nếu trùng ngày nghỉ/tự học thì dời sang ngày học tiếp theo trong tuần đó
        $findExamDate = function ($lastDay, $holidayDates, $selfStudyDays) {
            $examDate = $lastDay->copy()->addWeek(); // sang tuần sau, cùng thứ
            $maxCheck = 6; // kiểm tra tối đa 6 ngày trong tuần đó
            $checked = 0;
            while (
                (isset($holidayDates[$examDate->format('Y-m-d')]) ||
                    isset($selfStudyDays[$examDate->format('Y-m-d')])) &&
                $checked < $maxCheck
            ) {
                $examDate->addDay();
                $checked++;
                if ($examDate->dayOfWeek == Carbon::SUNDAY) {
                    break;
                }
            }
            return $examDate;
        };
    @endphp
    <section class="section">
        <div class="section-header">
            <h1>Lịch giảng dạy - {{ $schedule->TenTKB }}</h1>
        </div>

        <div class="row justify-content-center my-5">
            <div class="col-lg-12">
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
                                HỌC KỲ {{ $hocki->TenHK }}
                            </h2>
                        </div>

                        <div class="row justify-content-between mb-4">
                            <div class="col-md-6 text-start">
                                <p class="mb-1"><strong>Mã lớp:</strong> <span
                                        style="color: red;">{{ $schedule->MaLop }}</span></p>
                                @if ($phonglt)
                                    <p class="mb-1"><strong>Phòng lý thuyết:</strong> <span
                                            style="color: red;">{{ $phonglt->TenPhong }}</span></p>
                                @endif
                                @if ($phongth)
                                    <p class="mb-1"><strong>Phòng thực hành:</strong> <span
                                            style="color: red;">{{ $phongth->TenPhong }}</span></p>
                                @endif
                            </div>
                            <div class="col-md-6 text-start">
                                <p class="mb-1"><strong>Bắt đầu học từ ngày:</strong> <span
                                        style="color: red;">{{ $startDate->format('d/m/Y') }}</span></p>
                                <p class="mb-1"><strong>Tổng số tuần:</strong> <span
                                        style="color: red;">{{ $totalWeeks }}</span></p>
                                <p class="mb-1"><strong>Khung giờ:</strong> <span
                                        style="color: red;">{{ $dsmh && $dsmh->khungGio ? $dsmh->khungGio->ThoiGian : $dsmh->TenKhungGio ?? 'Chưa có' }}</span>
                                </p>
                            </div>
                        </div>

                        @foreach ($teacherSchedules as $maGV => $teacherData)
                            <div class="mb-5">
                                <h3 class="mb-3">Giảng viên: {{ $teacherData['info']->HoTenGV }}</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover rounded shadow-sm bg-white">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="text-center">NGÀY</th>
                                                <th class="text-center">TUẦN</th>
                                                @foreach ($weekDaysFull as $day)
                                                    <th class="text-center">{{ $day }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($teacherData['schedule'] as $week => $days)
                                                @php
                                                    $weekDates = collect($days)->pluck('date')->toArray();
                                                    // Tìm ngày bất kỳ trong tuần để xác định tuần
                                                    $anyDate = null;
                                                    foreach ($days as $d) {
                                                        if (!empty($d['date'])) {
                                                            $anyDate = \Carbon\Carbon::createFromFormat(
                                                                'd/m/Y',
                                                                $d['date'],
                                                            );
                                                            break;
                                                        }
                                                    }
                                                    if ($anyDate) {
                                                        $monday = $anyDate
                                                            ->copy()
                                                            ->startOfWeek(\Carbon\Carbon::MONDAY)
                                                            ->format('d/m/Y');
                                                        $saturday = $anyDate
                                                            ->copy()
                                                            ->startOfWeek(\Carbon\Carbon::MONDAY)
                                                            ->addDays(5)
                                                            ->format('d/m/Y');
                                                    } else {
                                                        $monday = $saturday = '';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="text-wrap align-middle text-center" style="width: 12rem;">
                                                        {{ $monday }} <br> - <br> {{ $saturday }}
                                                    </td>
                                                    <td class="text-wrap align-middle text-center">{{ $week }}</td>
                                                    @foreach ($weekDaysFull as $day)
                                                        @php $dayData = $days[$day] ?? ['subject' => '-', 'is_exam' => false, 'MaMH' => null]; @endphp
                                                        <td
                                                            class="text-wrap align-middle text-center
                                                                @if (strpos($dayData['subject'], 'Thi') === 0) event-exam
                                                                @elseif (in_array($dayData['subject'], $allHolidayNames))
                                                                    event-holiday @endif
                                                            ">
                                                            @if ($dayData['is_exam'] ?? false)
                                                                @php
                                                                    $maMH = $dayData['MaMH'] ?? null;
                                                                    $tenMH =
                                                                        $subjectOccurrences[$maMH]['TenMH'] ??
                                                                        \App\Models\danhsachmonhoc::where(
                                                                            'MaMH',
                                                                            $maMH,
                                                                        )->value('TenMH');
                                                                @endphp
                                                                <span class="event-exam">Thi {{ $tenMH }}
                                                                    ({{ $maMH }})
                                                                </span>
                                                            @elseif (isset($subjectOccurrences[$dayData['subject']]))
                                                                {{ $subjectOccurrences[$dayData['subject']]['TenMH'] }}
                                                                @php
                                                                    $giangDay = \App\Models\GiangDay::where(
                                                                        'MaMH',
                                                                        $dayData['subject'],
                                                                    )
                                                                        ->where('MaLop', $schedule->MaLop)
                                                                        ->first();
                                                                    if ($giangDay) {
                                                                        $giangVien = \App\Models\giaovien::where(
                                                                            'MaGV',
                                                                            $giangDay->MaGV,
                                                                        )->first();
                                                                        if ($giangVien) {
                                                                            echo ' - GV: ' . $giangVien->HoTenGV;
                                                                        }
                                                                    }
                                                                @endphp
                                                            @elseif ($dayData['subject'] === 'self-study')
                                                                -
                                                            @else
                                                                {{ $dayData['subject'] }}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                            <a href="{{ route('exportTeacherSchedule', $schedule->TenTKB) }}" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                            <a href="{{ route('schedule', $schedule->TenTKB) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
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

    .table,
    .table th,
    .table td {
        font-size: 16px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

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

    .event-exam {
        color: #007bff !important;
        font-weight: bold !important;
        font-size: 16px;
    }

    .event-holiday {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }

    .text-wrap {
        white-space: pre-line;
        word-break: break-word;
    }

    .fas,
    .far,
    .fa-brands,
    .fa-regular {
        margin-right: 4px;
    }

    .card {
        border-radius: 8px;
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 1.5rem;
    }

    .event-start {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .event-end {
        background-color: #6f42c1 !important;
        border-color: #6f42c1 !important;
    }

    .event-self-study {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }

    .table td,
    .table th {
        width: 12rem;
    }

    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
    }

    .table {
        min-width: 1200px;
    }

    .table td:nth-child(1),
    .table th:nth-child(1),
    .table td:nth-child(2),
    .table th:nth-child(2) {
        font-size: 16px;
        font-weight: 500;
    }
</style>
