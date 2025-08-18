@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Lịch học theo tuần</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (isset($schedule))
            <div class="mb-3">
                <strong>Lớp:</strong> {{ $schedule->MaLop }}<br>
                <strong>Học kỳ:</strong> {{ $hocki->TenHK ?? '' }}<br>
                <strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y') }}
            </div>
            <form method="get" class="mb-3 d-flex align-items-center gap-2">
                <label for="week" class="mb-0 mr-2">Chọn tuần:</label>
                <select name="week" id="week" class="mr-4" onchange="this.form.submit()"
                    {{ request('viewMode', 'week') == 'all' ? 'disabled' : '' }}>
                    @for ($i = 1; $i <= $totalWeeks; $i++)
                        <option value="{{ $i }}" {{ $selectedWeek == $i ? 'selected' : '' }}>Tuần
                            {{ $i }}</option>
                    @endfor
                </select>
                <input type="hidden" name="viewMode" value="{{ $viewMode }}">
                <button type="submit" name="viewMode" value="week"
                    class="btn btn-primary mr-4 btn-sm {{ $viewMode == 'week' ? 'active' : '' }}">Xem theo tuần</button>
                <button type="submit" name="viewMode" value="all"
                    class="btn btn-secondary btn-sm {{ $viewMode == 'all' ? 'active' : '' }}">Xem toàn bộ</button>
                @isset($exportUrl)
                    <a href="{{ $exportUrl }}" class="btn btn-outline-info btn-sm ml-auto">
                        <i class="fas fa-print"></i> In lịch
                    </a>
                @endisset
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm bg-white">
                    <thead class="table-primary">
                        <tr>
                            <th>Tuần</th>
                            @foreach ($weekDays as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $weeksToShow = $viewMode == 'all' ? range(1, $totalWeeks) : [$selectedWeek];
                        @endphp
                        @foreach ($weeksToShow as $week)
                            @if (isset($scheduleMatrix[$week]))
                                <tr>
                                    @php
                                        // Lấy ngày đầu và cuối tuần
                                        $firstDay = $scheduleMatrix[$week][$weekDays[0]]['date'] ?? '';
                                        $lastDay =
                                            $scheduleMatrix[$week][$weekDays[count($weekDays) - 1]]['date'] ?? '';
                                    @endphp
                                    <td class="align-middle text-center">
                                        Tuần {{ $week }}<br>
                                        <span class="small text-muted">{{ $firstDay }} - {{ $lastDay }}</span>
                                    </td>
                                    @foreach ($weekDays as $day)
                                        @php $cell = $scheduleMatrix[$week][$day] ?? null; @endphp
                                        <td class="align-middle text-center" style="{{ $cell['style'] ?? '' }}">
                                            @if ($cell)
                                                @php
                                                    $subject = $cell['subject'];
                                                    $tenMH = null;
                                                    if (
                                                        isset($subjectOccurrences) &&
                                                        isset($subjectOccurrences[$subject])
                                                    ) {
                                                        $tenMH = $subjectOccurrences[$subject]['TenMH'];
                                                    } elseif (isset($monhocs)) {
                                                        $mh = $monhocs->where('MaMH', $subject)->first();
                                                        $tenMH = $mh ? $mh->TenMH : null;
                                                    }

                                                    // Lấy phòng học
                                                    $phong = null;
                                                    if (isset($phongHocTheoNgay) && isset($cell['date'])) {
                                                        $ngayCell = \Carbon\Carbon::createFromFormat(
                                                            'd/m/Y',
                                                            $cell['date'],
                                                        )->format('Y-m-d');
                                                        $phong =
                                                            $phongHocTheoNgay[$ngayCell] ?? $phongHocTheoNgay->first();
                                                    }
                                                    // Lấy thời gian học
                                                    $thoiGian =
                                                        $dsmh->khungGio->ThoiGian ?? ($dsmh->TenKhungGio ?? '-');
                                                @endphp
                                                <div>
                                                    <span class="fw-bold">{{ $tenMH ?? $subject }}</span>
                                                    @if ($subject && str_starts_with($subject, 'Thi'))
                                                        <span class="text-danger ms-1">(Thi)</span>
                                                    @elseif($subject && str_contains($subject, 'tự học'))
                                                        <span class="badge bg-success ms-1">Tự học</span>
                                                    @elseif($subject && str_contains($subject, 'nghỉ'))
                                                        <span class="badge bg-warning ms-1">Nghỉ</span>
                                                    @endif
                                                </div>
                                                @if (
                                                    $tenMH &&
                                                        !(
                                                            $subject &&
                                                            (str_starts_with($subject, 'Thi') || str_contains($subject, 'tự học') || str_contains($subject, 'nghỉ'))
                                                        ))
                                                    <div class="small text-muted">
                                                        <i class="fas fa-clock"></i> {{ $thoiGian }}<br>
                                                        <i class="fas fa-door-open"></i> {{ $phong->TenPhong ?? '-' }}<br>
                                                        @php

                                                            // Lấy tên giảng viên
                                                            $tenGV = '-';
                                                            if (isset($subject) && $subject) {
                                                                $giangDay = \App\Models\GiangDay::where(
                                                                    'MaMH',
                                                                    $subject,
                                                                )
                                                                    ->where('MaLop', $schedule->MaLop)
                                                                    ->first();
                                                                if ($giangDay && $giangDay->giaovien) {
                                                                    $tenGV = $giangDay->giaovien->HoTenGV;
                                                                }
                                                            }
                                                        @endphp
                                                        <i class="fas fa-user-tie"></i> GV: {{ $tenGV }}<br>

                                                    </div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection

@push('styles')
    <style>
        /* Cải thiện khả năng đọc và khoảng cách như giao diện mẫu */
        .table { table-layout: fixed; }
        .table th, .table td { vertical-align: top !important; padding: 10px 12px; }
        .table thead th { font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: .3px; }
        .table tbody td { font-size: 14px; line-height: 1.35; }
        .table tbody td .fw-bold { font-size: 14px; }
        .table tbody td .small { font-size: 12px; }
        .badge { font-size: 85%; padding: 4px 8px; }
        .btn.active { font-weight: 600; }
        /* Ô nội dung lịch học có nền nhẹ và bo góc giống thẻ sự kiện */
        .event-card { background: #eaf7ff; border: 1px solid #d6ecff; border-left: 4px solid #4da3ff; border-radius: 6px; padding: 8px 10px; }
        .event-card.exam { background: #fff6e5; border-color: #ffe0a3; border-left-color: #ffb84d; }
        .event-card.self-study { background: #ebffef; border-color: #cdf5d5; border-left-color: #52c27f; }
        .event-card.holiday { background: #fff; border-color: #f0f0f0; border-left-color: #ff6868; }
        /* Căn giữa nội dung nhẹ */
        td.align-middle.text-center > .event-wrapper { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .legend { display: inline-block; width: 14px; height: 10px; border-radius: 2px; margin-right: 6px; vertical-align: middle; }
    </style>
@endpush
