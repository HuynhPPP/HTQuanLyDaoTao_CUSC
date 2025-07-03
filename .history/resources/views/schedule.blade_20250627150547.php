@extends('layouts.new_app.master')

@section('main-content')
    @php
        use Carbon\Carbon;
        use App\Models\danhsachngaynghi;
        use App\Models\ngaytuhoc;
        use App\Models\GiangDay;
        use App\Models\giaovien;
        use App\Models\danhsachphong;
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
        // Debug thử
        // dd(\App\Models\GiangDay::where('MaMH', 'MÃ_MÔN_HỌC')->where('MaLop', 'MÃ_LỚP')->first());
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
            $findExamDate = null,
            $selfStudyDaysArr = [],
            $weekDaysFull = [],
        ) use ($getTeacherInfo) {
            foreach ($subjectOccurrences as $subject => &$details) {
                if ($details['remaining'] > 0) {
                    if (is_null($details['first'])) {
                        $details['first'] = $currentDate;
                    }
                    $details['remaining'] -= 4;
                    if ($details['remaining'] <= 0) {
                        $details['last'] = $currentDate;
                        // === BẮT ĐẦU LOGIC NGÀY THI MỚI ===
                        $lastDay = $currentDate->copy();
                        $examDate = $lastDay->copy()->addWeek(); // sang tuần sau, cùng thứ
                        // Nếu ngày thi trùng ngày nghỉ/tự học thì dời sang ngày học tiếp theo trong tuần đó
                        $maxCheck = 6; // kiểm tra tối đa 6 ngày trong tuần đó
                        $checked = 0;
                        while (
                            (isset($holidayDates[$examDate->format('Y-m-d')]) ||
                                isset($selfStudyDays[$examDate->format('Y-m-d')])) &&
                            $checked < $maxCheck
                        ) {
                            $examDate->addDay();
                            $checked++;
                            // Nếu qua Chủ nhật thì dừng
                            if ($examDate->dayOfWeek == Carbon::SUNDAY) {
                                break;
                            }
                        }
                        // === KẾT THÚC LOGIC NGÀY THI MỚI ===
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
        // Các ngày trong tuần đầy đủ để hiển thị bảng
        $weekDaysFull = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
        $dayMap = [
            'THỨ HAI' => 0,
            'THỨ BA' => 1,
            'THỨ TƯ' => 2,
            'THỨ NĂM' => 3,
            'THỨ SÁU' => 4,
            'THỨ BẢY' => 5,
        ];
        // Tạo lịch học
        $scheduleMatrix = [];
        $examDays = [];
        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStart = $startDate
                ->copy()
                ->addWeeks($week - 1)
                ->startOfWeek();
            $scheduleMatrix[$week] = [];
            foreach ($weekDaysFull as $day) {
                $currentDate = $weekStart->copy()->addDays($dayMap[$day]);
                $subject = '';
                $style = '';
                // Ưu tiên kiểm tra ngày thi trước
                if (isset($examDays[$currentDate->format('Y-m-d')])) {
                    $subject = $examDays[$currentDate->format('Y-m-d')];
                    $style = 'color: blue; font-weight: bold; filter-bg-exam';
                } elseif (in_array($day, $weekDays)) {
                    if ($currentDate->gte($startDate)) {
                        if (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                            $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                            $style = 'color: black;';
                        } elseif (isset($holidayDates[$currentDate->format('Y-m-d')])) {
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
                    // Ngày không thuộc kiểu ngày học, nếu là ngày tự học thì hiển thị self-study, còn lại để trống
                    if (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                        $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                        $style = 'color: black;';
                    }
                }
                $scheduleMatrix[$week][$day] = [
                    'date' => $currentDate->format('d/m/Y'),
                    'subject' => $subject,
                    'style' => $style,
                ];
            }
        }
    @endphp
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif
    <section class="section font-nunito">
        <div class="section-header">
            <h1>{{ $schedule->TenTKB }}</h1>
        </div>
        <div class="row justify-content-center my-5">
            <div class="col-lg-12">
                <div class="card shadow rounded-4 border-0 mb-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo_CTU.png') }}" alt="logo" width="80" class="mb-3">
                            <h5 class="fw-bold text-primary mb-1">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
                            <h2 class="fw-bold mb-2">CANTHO UNIVERSITY SOFTWARE CENTER</h2>
                            <p class="text-secondary mb-0 fw-bold">Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel:
                                0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
                        </div>
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">
                                THỜI KHÓA BIỂU LỚP {{ $schedule->MaLop }} - {{ $chuongTrinhName }}<br>
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
                                @if (isset($phongs) && count($phongs) > 0)
                                    @foreach ($phongs as $phong)
                                        <p class="mb-1">
                                            <strong>Phòng học:</strong>
                                            <span style="color: red;">{{ $phong->TenPhong }}</span>
                                            @if ($phong->Ca)
                                                | <strong>Thời gian:</strong> <span
                                                    style="color: red;">{{ $phong->Ca }}</span>
                                            @endif
                                        </p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
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
                                    @foreach ($scheduleMatrix as $week => $days)
                                        @php
                                            $weekDates = collect($days)->pluck('date')->toArray();
                                        @endphp
                                        <tr>
                                            <td class="text-wrap align-middle text-center" style="width: 12rem;">
                                                {{ reset($weekDates) }}<br>
                                                <span
                                                    style="display:inline-block;width:100%;text-align:center;">-</span><br>
                                                {{ end($weekDates) }}
                                            </td>
                                            <td class="text-wrap align-middle text-center">{{ $week }}</td>
                                            @foreach ($weekDaysFull as $day)
                                                @if (isset($days[$day]))
                                                    <td class="text-wrap align-middle text-center"
                                                        style="width: 12rem; {{ $days[$day]['subject'] === 'self-study' ? 'color: black;' : $days[$day]['style'] }}">
                                                        @if ($days[$day]['is_exam'] ?? false)
                                                            @php
                                                                $maMH = $days[$day]['MaMH'] ?? null;
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
                                                        @elseif (isset($subjectOccurrences[$days[$day]['subject']]))
                                                            {{ $subjectOccurrences[$days[$day]['subject']]['TenMH'] }}{!! $getTeacherInfo($days[$day]['subject']) !!}
                                                        @else
                                                            {{ $days[$day]['subject'] }}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td class="text-wrap align-middle text-center" style="width: 12rem;">-
                                                    </td>
                                                @endif
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
                            <button type="button" class="btn btn-warning text-white" data-toggle="modal"
                                data-target="#SelfStudyModal">
                                <i class="fa-brands fa-leanpub"></i> Tự học
                            </button>

                            <!-- Nút Chỉnh sửa -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#EditTKBModal">
                                <i class="fa-regular fa-calendar"></i> Chỉnh sửa
                            </button>

                            <!-- Nút Chọn Môn Học -->
                            <button type="button" class="btn btn-secondary" data-toggle="modal"
                                data-target="#selectSubjectModal">
                                <i class="fas fa-plus"></i> Chọn Môn Học
                            </button>

                            <!-- Nút Xem lịch giảng viên -->
                            <a href="{{ route('teacherSchedule', $schedule->TenTKB) }}" class="btn btn-info">
                                <i class="fas fa-chalkboard-teacher"></i> Lịch giảng viên
                            </a>
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
                        <div class="form-group">
                            <label for="ngayHocType">Kiểu ngày học</label>
                            <select class="form-control" id="ngayHocType" name="ngayHocType" required>
                                <option value="all" {{ $schedule->ngayHocType == 'all' ? 'selected' : '' }}>Cả tuần
                                    (T2-T7)</option>
                                <option value="chan" {{ $schedule->ngayHocType == 'chan' ? 'selected' : '' }}>Chẵn
                                    (T2-T4-T6)</option>
                                <option value="le" {{ $schedule->ngayHocType == 'le' ? 'selected' : '' }}>Lẻ
                                    (T3-T5-T7)</option>
                            </select>
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

    <!-- Modal Chọn Môn Học -->
    <div class="modal fade" id="selectSubjectModal" tabindex="-1" role="dialog"
        aria-labelledby="selectSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectSubjectModalLabel">Chọn Môn Học</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="subjectsTable">
                            <thead>
                                <tr>
                                    <th>Mã Môn Học</th>
                                    <th>Tên Môn Học</th>
                                    <th>Giờ Gốc</th>
                                    <th>Giờ Triển Khai</th>
                                    <th>Loại Tiết Học</th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="confirmSubjectSelection">Xác nhận</button>
                </div>
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

    .filter-bg-self-study {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
</style>

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Xử lý xóa lịch học
            $('.delete-schedule').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa lịch học này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Xử lý form ngày nghỉ
            $('#absenceModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                Swal.fire({
                    title: 'Xác nhận thêm ngày nghỉ?',
                    text: 'Bạn có chắc chắn muốn thêm ngày nghỉ này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Thêm',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Xử lý form tự học
            $('#SelfStudyModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                Swal.fire({
                    title: 'Xác nhận thêm ngày tự học?',
                    text: 'Bạn có chắc chắn muốn thêm ngày tự học này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Thêm',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Xử lý form chỉnh sửa TKB
            $('#EditTKBModal form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                Swal.fire({
                    title: 'Xác nhận thay đổi?',
                    text: 'Bạn có chắc chắn muốn thay đổi ngày khai giảng?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Lưu',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Giờ bắt đầu phải trước giờ kết thúc',
                        icon: 'error'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận thêm khung giờ?',
                    text: 'Bạn có chắc chắn muốn thêm khung giờ này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Thêm',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Validate giờ học
            $('#timeSlotModal form').on('change', 'input[type="time"]', function() {
                const gioBD = $('#GioBD').val();
                const gioKT = $('#GioKT').val();

                if (gioBD && gioKT && gioBD >= gioKT) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Giờ bắt đầu phải trước giờ kết thúc',
                        icon: 'error'
                    });
                }
            });

            // Load danh sách môn học
            function loadSubjects() {
                console.log('Fetching subjects...');
                $.get('/get-subjects', function(subjects) {
                    console.log('Subjects received:', subjects);
                    const tbody = $('#subjectsTable tbody');
                    tbody.empty();

                    if (subjects && subjects.length > 0) {
                        subjects.forEach(function(subject) {
                            let lessonType = [];
                            if (subject.TietLT) lessonType.push('Lý thuyết');
                            if (subject.TietTH) lessonType.push('Thực hành');
                            if (subject.TietLTvaTH) lessonType.push('LT và TH');

                            const row = `
                                <tr>
                                    <td>${subject.MaMH}</td>
                                    <td>${subject.TenMH}</td>
                                    <td>${subject.GioGoc}</td>
                                    <td>${subject.GioTrienKhai}</td>
                                    <td>${lessonType.join(', ')}</td>
                                    <td>
                                        <input type="checkbox" class="subject-checkbox" 
                                               data-mamh="${subject.MaMH}"
                                               data-tenmh="${subject.TenMH}"
                                               data-giotrienkhai="${subject.GioTrienKhai}">
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    } else {
                        console.log('No subjects received or data is empty.');
                        tbody.append(
                            '<tr><td colspan="6" class="text-center">Không có môn học nào trong database.</td></tr>'
                        );
                    }

                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching subjects:', textStatus, errorThrown, jqXHR);
                    const tbody = $('#subjectsTable tbody');
                    tbody.empty();
                    tbody.append(
                        '<tr><td colspan="6" class="text-center text-danger">Lỗi khi tải danh sách môn học. Vui lòng kiểm tra console để biết thêm chi tiết.</td></tr>'
                    );
                });
            }

            // Mở modal chọn môn học
            $('#selectSubjectModal').on('show.bs.modal', function() {
                loadSubjects();
            });

            // Xử lý xác nhận chọn môn học
            $('#confirmSubjectSelection').click(function() {
                const selectedSubjects = [];
                $('.subject-checkbox:checked').each(function() {
                    const tenMH = $(this).data('tenmh');
                    const gioTrienKhai = $(this).data('giotrienkhai');
                    const mamh = $(this).data('mamh');
                    const tenMHTrim = tenMH.trim();
                    selectedSubjects.push({
                        MaMH: mamh,
                        TenMH: tenMHTrim,
                        GioTrienKhai: gioTrienKhai
                    });
                });

                if (selectedSubjects.length === 0) {
                    Swal.fire({
                        title: 'Cảnh báo!',
                        text: 'Vui lòng chọn ít nhất một môn học',
                        icon: 'warning'
                    });
                    return;
                }

                // Gửi dữ liệu môn học đã chọn đến server
                $.ajax({
                    url: '{{ route('updateScheduleSubjects', ['TenTKB' => $schedule->TenTKB]) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        subjects: selectedSubjects
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: 'Đã cập nhật môn học thành công.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href =
                                '{{ route('schedule', ['TenTKB' => $schedule->TenTKB]) }}';
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi cập nhật môn học.' + (jqXHR
                                .responseJSON && jqXHR.responseJSON.message ? ' ' +
                                jqXHR.responseJSON.message : ''),
                            icon: 'error'
                        });
                        console.error('Error updating subjects:', textStatus, errorThrown,
                            jqXHR);
                    }
                });

                // Đóng modal ngay lập tức sau khi gửi dữ liệu
                $('#selectSubjectModal').modal('hide');
            });
        });
    </script>
@endsection
