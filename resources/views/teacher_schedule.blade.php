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
    @endphp
    <section class="section">
        <div class="section-header">
            <h1>Lịch giảng dạy - {{ $schedule->TenTKB }}</h1>
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
                                HỌC KỲ {{ $hocki->TenHK }}
                            </h2>
                        </div>

                        <div class="row justify-content-between mb-4">
                            <div class="col-md-6 text-start">
                                <p class="mb-1"><strong>Mã lớp:</strong> <span
                                        style="color: red;">{{ $schedule->MaLop }}</span></p>
                                <p class="mb-1"><strong>Phòng lý thuyết:</strong> <span
                                        style="color: red;">{{ $phonglt->TenPhong ?? 'Chưa có' }}</span></p>
                                <p class="mb-1"><strong>Phòng thực hành:</strong> <span
                                        style="color: red;">{{ $phongth->TenPhong ?? 'Chưa có' }}</span></p>
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
                                                @foreach ($weekDays as $day)
                                                    <th class="text-center">{{ $day }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($teacherData['schedule'] as $week => $days)
                                                @php
                                                    // Lấy ngày bắt đầu và kết thúc của tuần trực tiếp từ scheduleMatrix
                                                    $startDateOfWeek = $scheduleMatrix[$week]['THỨ HAI']['date'] ?? '';
                                                    $endDateOfWeek = $scheduleMatrix[$week]['THỨ BẢY']['date'] ?? '';
                                                @endphp
                                                <tr>
                                                    <td class="text-wrap align-middle text-center" style="width: 12rem;">
                                                        {{ $startDateOfWeek }} - {{ $endDateOfWeek }}
                                                    </td>
                                                    <td class="text-wrap align-middle text-center">{{ $week }}</td>
                                                    @foreach ($days as $dayData)
                                                        <td
                                                            class="text-wrap align-middle text-center
                                                                @if (strpos($dayData['subject'], 'Thi') === 0) event-exam
                                                                @elseif (in_array($dayData['subject'], $allHolidayNames))
                                                                    event-holiday @endif
                                                            ">
                                                            @if ($dayData['subject'])
                                                                @if (strpos($dayData['subject'], 'Thi') === 0)
                                                                    @php
                                                                        $examSubjectTenMH = substr(
                                                                            $dayData['subject'],
                                                                            4,
                                                                        );
                                                                        $examMonHoc = \App\Models\danhsachmonhoc::where(
                                                                            'TenMH',
                                                                            $examSubjectTenMH,
                                                                        )->first();
                                                                        $examSubjectMaMH = $examMonHoc
                                                                            ? $examMonHoc->MaMH
                                                                            : null;

                                                                        $isTeacherAssignedToSubject = false;
                                                                        if ($examSubjectMaMH) {
                                                                            $isTeacherAssignedToSubject = \App\Models\GiangDay::where(
                                                                                'MaGV',
                                                                                $maGV,
                                                                            )
                                                                                ->where('MaMH', $examSubjectMaMH)
                                                                                ->where('MaLop', $schedule->MaLop)
                                                                                ->exists();
                                                                        }
                                                                    @endphp
                                                                    @if ($isTeacherAssignedToSubject && $examMonHoc)
                                                                        {{ $dayData['subject'] }}
                                                                    @else
                                                                        -
                                                                    @endif
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
    }

    .event-holiday {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
</style>
