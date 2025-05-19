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
    {{-- <section class="section">
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
                            <form id="deleteScheduleForm" class="m-0"
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
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#timeSlotModal">
                                <i class="fas fa-clock"></i> Khung giờ
                            </button>

                            <!-- Nút Ngày nghỉ -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#absenceModal">
                                <i class="fas fa-plus"></i> Ngày nghỉ
                            </button>

                            <!-- Nút Tự học -->
                            <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#SelfStudyModal">
                                <i class="fa-brands fa-leanpub"></i> Tự học
                            </button>

                            <!-- Nút Chỉnh sửa -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#EditTKBModal">
                                <i class="fa-regular fa-calendar"></i> Chỉnh sửa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="section">
        <div class="section-header">
            <h1>{{ $schedule->TenTKB }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">Thời khóa biểu</div>
                <div class="breadcrumb-item active">{{ $schedule->TenTKB }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center w-100">
                            <img src="{{ asset('images/logo_CTU.png') }}" alt="logo" width="80" class="mb-3">
                            <h5 class="text-primary">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
                            <h2 class="mb-2">CANTHO UNIVERSITY SOFTWARE CENTER</h2>
                            <p class="text-muted">Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ</p>
                            <p class="text-muted">Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted">Mã lớp:</h6>
                                    <h5>{{ $schedule->MaLop }}</h5>
                                </div>
                                <div class="text-muted">
                                    <span>Ver: {{ $chuongtrinh->PhienBan }}</span> |
                                    <span>{{ \Carbon\Carbon::parse($chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-2">
                                    <h6 class="text-muted">Bắt đầu học từ ngày:</h6>
                                    <h5>{{ \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y') }}</h5>
                                </div>
                                <div class="info-item mb-2">
                                    <h6 class="text-muted">Phòng học lý thuyết:</h6>
                                    <h5>{{ $phonglt->TenPhong ?? 'Chưa có' }}</h5>
                                </div>
                                <div class="info-item">
                                    <h6 class="text-muted">Phòng học thực hành:</h6>
                                    <h5>{{ $phongth->TenPhong ?? 'Chưa có' }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Calendar View -->
                        <div class="fc-overflow">
                            <div id="myCalendar"></div>
                        </div>

                        <!-- Table View -->
                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-bordered">
                                <thead>
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
                                        <!-- ... existing code ... -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-center">
                            <div class="buttons">
                                <form id="deleteScheduleForm" class="d-inline"
                                    action="{{ route('deleteSchedule', ['TenTKB' => $schedule->TenTKB]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-icon btn-danger delete-schedule"
                                        data-toggle="tooltip" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('exportExcel', $schedule->TenTKB) }}" class="btn btn-icon btn-primary"
                                    data-toggle="tooltip" title="Xuất Excel">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                                <button type="button" class="btn btn-icon btn-info" data-toggle="modal"
                                    data-target="#timeSlotModal" title="Khung giờ">
                                    <i class="fas fa-clock"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-success" data-toggle="modal"
                                    data-target="#absenceModal" title="Thêm ngày nghỉ">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-warning" data-toggle="modal"
                                    data-target="#SelfStudyModal" title="Thêm ngày tự học">
                                    <i class="fa-brands fa-leanpub"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-primary" data-toggle="modal"
                                    data-target="#EditTKBModal" title="Chỉnh sửa">
                                    <i class="fa-regular fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Ngày nghỉ -->
    <div class="modal fade" id="absenceModal" tabindex="-1" role="dialog" aria-labelledby="absenceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="absenceModalLabel">Thêm ngày nghỉ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('saveholiday', $schedule->TenTKB) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="TenNgayNghi">Tên ngày nghỉ</label>
                            <input type="text" class="form-control" id="TenNgayNghi" name="TenNgayNghi" required>
                        </div>
                        <div class="form-group">
                            <label for="NgayBDNghi">Ngày bắt đầu nghỉ</label>
                            <input type="date" class="form-control" id="NgayBDNghi" name="NgayBDNghi" required>
                        </div>
                        <div class="form-group">
                            <label for="NgayKT">Ngày kết thúc</label>
                            <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tự học -->
    <div class="modal fade" id="SelfStudyModal" tabindex="-1" role="dialog" aria-labelledby="SelfStudyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SelfStudyModalLabel">Thêm ngày tự học</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('saveSelfStudy', $schedule->TenTKB) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="TenNgayTuHoc">Tên ngày tự học</label>
                            <input type="text" class="form-control" id="TenNgayTuHoc" name="TenNgayTuHoc" required>
                        </div>
                        <div class="form-group">
                            <label for="NgayBDTuHoc">Ngày bắt đầu tự học</label>
                            <input type="date" class="form-control" id="NgayBDTuHoc" name="NgayBDTuHoc" required>
                        </div>
                        <div class="form-group">
                            <label for="NgayKTTuHoc">Ngày kết thúc</label>
                            <input type="date" class="form-control" id="NgayKTTuHoc" name="NgayKTTuHoc" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh sửa TKB -->
    <div class="modal fade" id="EditTKBModal" tabindex="-1" role="dialog" aria-labelledby="EditTKBModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditTKBModalLabel">Chỉnh sửa thời khóa biểu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('EditTKB', $schedule->TenTKB) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="NgayHoc">Ngày khai giảng</label>
                            <input type="date" class="form-control" id="NgayHoc" name="NgayHoc"
                                value="{{ $schedule->NgayHoc }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Khung giờ -->
    <div class="modal fade" id="timeSlotModal" tabindex="-1" role="dialog" aria-labelledby="timeSlotModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeSlotModalLabel">Thêm khung giờ học</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('saveTimeSlot', $schedule->TenTKB) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="TenKhungGio">Tên khung giờ</label>
                            <input type="text" class="form-control" id="TenKhungGio" name="TenKhungGio"
                                placeholder="VD: Sáng: 7:30-11:30, Chiều: 13:30-17:30" required>
                        </div>
                        <div class="form-group">
                            <label for="GioBD">Giờ bắt đầu</label>
                            <input type="time" class="form-control" id="GioBD" name="GioBD" required>
                        </div>
                        <div class="form-group">
                            <label for="GioKT">Giờ kết thúc</label>
                            <input type="time" class="form-control" id="GioKT" name="GioKT" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

    /* Reset margin for form */
    #deleteScheduleForm {
        margin: 0;
        display: inline-block;
    }

    .btn-danger {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
    }

    .btn-danger:hover {
        background-color: #ff5252;
        border-color: #ff5252;
    }

    .btn-primary {
        background-color: #6c5ce7;
        border-color: #6c5ce7;
    }

    .btn-primary:hover {
        background-color: #5f3dc4;
        border-color: #5f3dc4;
    }

    .btn-info {
        background-color: #00b8d4;
        border-color: #00b8d4;
    }

    .btn-info:hover {
        background-color: #00a0bc;
        border-color: #00a0bc;
    }

    .btn-success {
        background-color: #00e676;
        border-color: #00e676;
    }

    .btn-success:hover {
        background-color: #00c853;
        border-color: #00c853;
    }

    .btn-warning {
        background-color: #ffa726;
        border-color: #ffa726;
        color: white;
    }

    .btn-warning:hover {
        background-color: #fb8c00;
        border-color: #fb8c00;
        color: white;
    }

    /* CSS cho modal */
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-radius: 8px 8px 0 0;
        padding: 15px;
        border-bottom: 1px solid #ebedf2;
    }

    .modal-body {
        padding: 15px;
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

    /* CSS cho các icon */
    .fas,
    .far,
    .fa-brands,
    .fa-regular {
        margin-right: 4px;
    }

    /* CSS cho form controls */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
        font-size: 13px;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Khởi tạo calendar
            $('#myCalendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek'
                },
                events: [
                    // Chuyển đổi dữ liệu từ scheduleMatrix sang định dạng events
                    @foreach ($scheduleMatrix as $week => $days)
                        @foreach ($days as $day => $data)
                            @if ($data['subject'])
                                {
                                    title: '{{ $data['subject'] }}',
                                    start: '{{ \Carbon\Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d') }}',
                                    className: '{{ getEventClass($data['style']) }}'
                                },
                            @endif
                        @endforeach
                    @endforeach
                ],
                eventRender: function(event, element) {
                    element.tooltip({
                        title: event.title
                    });
                }
            });

            // Hàm chuyển đổi style sang class
            function getEventClass(style) {
                if (style.includes('red')) return 'event-start';
                if (style.includes('purple')) return 'event-end';
                if (style.includes('blue')) return 'event-exam';
                if (style.includes('green')) return 'event-self-study';
                if (style.includes('yellow')) return 'event-holiday';
                return '';
            }

            // Xử lý xóa lịch học
            $('.delete-schedule').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa lịch học này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    buttons: ['Hủy', 'Xóa'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal('Thao tác đã bị hủy.');
                    }
                });
            });

            // Xử lý form ngày nghỉ
            $('#absenceModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                swal({
                    title: 'Xác nhận thêm ngày nghỉ?',
                    text: 'Bạn có chắc chắn muốn thêm ngày nghỉ này?',
                    icon: 'warning',
                    buttons: ['Hủy', 'Thêm'],
                    dangerMode: false,
                }).then((willAdd) => {
                    if (willAdd) {
                        form.off('submit').submit();
                    }
                });
            });

            // Xử lý form tự học
            $('#SelfStudyModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                swal({
                    title: 'Xác nhận thêm ngày tự học?',
                    text: 'Bạn có chắc chắn muốn thêm ngày tự học này?',
                    icon: 'warning',
                    buttons: ['Hủy', 'Thêm'],
                    dangerMode: false,
                }).then((willAdd) => {
                    if (willAdd) {
                        form.off('submit').submit();
                    }
                });
            });

            // Xử lý form chỉnh sửa TKB
            $('#EditTKBModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                swal({
                    title: 'Xác nhận thay đổi?',
                    text: 'Bạn có chắc chắn muốn thay đổi ngày khai giảng?',
                    icon: 'warning',
                    buttons: ['Hủy', 'Lưu'],
                    dangerMode: false,
                }).then((willEdit) => {
                    if (willEdit) {
                        form.off('submit').submit();
                    }
                });
            });

            // Thêm xử lý cho form khung giờ
            $('#timeSlotModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const gioBD = $('#GioBD').val();
                const gioKT = $('#GioKT').val();

                if (gioBD >= gioKT) {
                    swal({
                        title: 'Lỗi!',
                        text: 'Giờ bắt đầu phải trước giờ kết thúc',
                        icon: 'error'
                    });
                    return;
                }

                swal({
                    title: 'Xác nhận thêm khung giờ?',
                    text: 'Bạn có chắc chắn muốn thêm khung giờ này?',
                    icon: 'warning',
                    buttons: ['Hủy', 'Thêm'],
                    dangerMode: false,
                }).then((willAdd) => {
                    if (willAdd) {
                        form.off('submit').submit();
                    }
                });
            });

            // Validate giờ học
            $('#timeSlotModal form').on('change', 'input[type="time"]', function() {
                const gioBD = $('#GioBD').val();
                const gioKT = $('#GioKT').val();

                if (gioBD && gioKT && gioBD >= gioKT) {
                    swal({
                        title: 'Lỗi!',
                        text: 'Giờ bắt đầu phải trước giờ kết thúc',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
@endsection
