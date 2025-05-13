@extends('layouts.new_app.master')

@section('main-content')
    @php
        use Carbon\Carbon;
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
        $totalWeeks = ceil($totalHours / 10);
        //lọc môn học có giờ triển khai nhiều hơn 0
        $filteredMonHocs = [];
        $subjectCount = count($monhocs);
        foreach ($monhocs as $index => $monhoc) {
            if ($monhoc->GioTrienKhai > 0) {
                $filteredMonHocs[] = $monhoc;
            }
        }
        // Đếm số môn học
        $subjectOccurrences = [];
        $subjectCount = count($filteredMonHocs);
        foreach ($filteredMonHocs as $index => $monhoc) {
            $subjectOccurrences[$monhoc->TenMH] = [
                'first' => null,
                'last' => null,
                'remaining' => $monhoc->GioTrienKhai,
            ];
            // Nếu là môn học cuối cùng, đánh dấu bằng chỉ số
            if ($index === $subjectCount - 1) {
                $subjectOccurrences[$monhoc->TenMH]['lastSubject'] = true;
            }
        }
        // Các ngày trong tuần
        $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU'];
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
        ) {
            foreach ($subjectOccurrences as $subject => &$details) {
                if ($details['remaining'] > 0) {
                    if (is_null($details['first'])) {
                        $details['first'] = $currentDate;
                    }
                    $details['remaining'] -= 2;
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
                        $examDays[$examDate->format('Y-m-d')] = "Thi $subject (E$examCounter) - L";
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
                        $style = 'color: blue; font-weight: bold;';
                        if ($subject === 'self-study') {
                            $style = 'text-dark';
                        }
                    } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                        $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                        $style = 'color: green; font-weight: bold;';
                    } else {
                        if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                            $subject = $holidayDates[$currentDate->format('Y-m-d')];
                            $style = 'background-color: yellow;';
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
                }
                $totalWeeks = ceil($totalHours / 10);
                $scheduleMatrix[$week][$day] = [
                    'date' => $currentDate->format('d/m/Y'),
                    'subject' => $subject,
                    'style' => $style,
                ];
            }
        }
    @endphp
    <section class="section" style="min-height: 100vh;">
        <div class="section-header">
            <h1>{{ $schedule->TenTKB }}</h1>
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
                            <h2 class="fw-bold text-success">{{ $schedule->TenTKB }}</h2>
                        </div>
                        <div class="row justify-content-between mb-4">
                            <div class="col-md-4 text-start">
                                <p class="mb-1"><strong>Mã lớp:</strong> {{ $schedule->MaLop }}</p>
                                <div class="small text-muted">
                                    <span>Ver: {{ $chuongtrinh->PhienBan }}</span> |
                                    <span>{{ \Carbon\Carbon::parse($chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-start">
                                <p class="mb-1">Bắt đầu học từ ngày:
                                    <strong>{{ \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y') }}</strong>
                                </p>
                                <p class="mb-1">Học Lý thuyết tại phòng:
                                    <strong>{{ $phonglt->TenPhong ?? ' Chưa có ' }}</strong>
                                </p>
                                <p class="mb-1">Học Thực hành tại phòng:
                                    <strong>{{ $phongth->TenPhong ?? ' Chưa có ' }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover rounded shadow-sm bg-white">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Tuần</th>
                                        <th>Giờ học</th>
                                        <th>THỨ HAI</th>
                                        <th>THỨ BA</th>
                                        <th>THỨ TƯ</th>
                                        <th>THỨ NĂM</th>
                                        <th>THỨ SÁU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scheduleMatrix as $week => $days)
                                        @php $weekDates = collect($days)->pluck('date')->toArray(); @endphp
                                        <tr>
                                            <th>{{ implode(' - ', [$weekDates[0], end($weekDates)]) }}</th>
                                            <th class="text-wrap align-middle">{{ $week }}</th>
                                            <th class="text-wrap align-middle" style="width: 12rem;">
                                                {{ $dsmh->TenKhungGio ?? '' }}</th>
                                            @foreach ($days as $dayData)
                                                <td class="text-wrap align-middle"
                                                    style="width: 12rem; {{ $dayData['style'] }}">
                                                    {{ $dayData['subject'] }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                            <form id="deleteScheduleForm"
                                action="{{ route('deleteSchedule', ['TenTKB' => $schedule->TenTKB]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-schedule">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                            <a href="{{ route('exportExcel', $schedule->TenTKB) }}" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Xuất
                            </a>
                            <!-- Nút Khung giờ -->
                            <button type="button" class="btn btn-primary" id="modal-addTime" title="Thêm khung giờ học">
                                <i class="fas fa-clock"></i> Khung giờ
                            </button>

                            <!-- Nút Ngày nghỉ -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#absenceModal"
                                data-toggle-tooltip="tooltip" title="Thêm ngày nghỉ">
                                <i class="fas fa-plus"></i> Ngày nghỉ
                            </button>

                            <!-- Nút Tự học -->
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#SelfStudyModal"
                                data-toggle-tooltip="tooltip" title="Thêm ngày tự học">
                                <i class="fa-brands fa-leanpub"></i> Tự học
                            </button>

                            <!-- Nút Chỉnh sửa -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#EditTKBModal"
                                data-toggle-tooltip="tooltip" title="Chỉnh sửa thời gian khai giảng">
                                <i class="fa-regular fa-calendar"></i> Chỉnh sửa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Các nút tròn chức năng ở góc dưới trái giữ nguyên -->

    @section('custom-js')
        <script>
            $(document).ready(function() {
                $('.delete-schedule').click(function(e) {
                    e.preventDefault(); // Ngăn submit mặc định
                    const form = $(this).closest('form'); // Tìm form cha gần nhất

                    swal({
                        title: 'Bạn có chắc chắn muốn lịch học này?',
                        text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                        icon: 'warning',
                        buttons: ['Hủy', 'Xóa'],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            form.submit(); // Xác nhận thì submit form
                        } else {
                            swal('Thao tác đã bị hủy.');
                        }
                    });
                });
            });
        </script>

        <!-- Absence Modal -->
        <script>
            $(document).ready(function() {
                $("#modal-addTime").fireModal({
                    title: 'Thêm Ngày Nghỉ',
                    body: `
                        <form id="absenceForm" method="POST" action="/save-url-here">
                            <div class="form-group">
                                <label for="TenNgayNghi" class="form-label">Tên ngày nghỉ:</label>
                                <textarea class="form-control" id="TenNgayNghi" name="TenNgayNghi" rows="1" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="NgayBDNghi" class="form-label">Ngày bắt đầu nghỉ:</label>
                                <input type="date" class="form-control" id="NgayBDNghi" name="NgayBDNghi" required>
                            </div>
                            <div class="form-group">
                                <label for="NgayKT" class="form-label">Ngày kết thúc:</label>
                                <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
                            </div>
                        </form>
                    `,
                    buttons: [{
                            text: 'Đóng',
                            class: 'btn btn-secondary',
                            handler: function(modal) {
                                modal.modal('hide');
                            }
                        },
                        {
                            text: 'Lưu',
                            class: 'btn btn-primary',
                            submit: true,
                            handler: function(modal) {
                                $('#absenceForm').on('submit', function(e) {
                                    if (!confirm('Bạn có chắc muốn lưu ngày nghỉ này?')) {
                                        e.preventDefault();
                                    }
                                });
                            }
                        }
                    ]
                });
            });
        </script>
    @endsection
</section>
@endsection
