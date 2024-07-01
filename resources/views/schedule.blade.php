@extends('layouts.app')

@section('content')

<div class="container my-5">
    <div class="row border border-3 rounded-3 mt-5 text-center">
        <div class="col my-5">
            <h5>TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h5>
            <h1>CANTHO UNIVERSITY SOFTWARE CENTER</h1>
            <p>Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn</p>
        </div>
        <div class="text-center mb-3">
            <h1>{{ $schedule->TenTKB }}</h1>
        </div>
        <div class="row justify-content-between">
            <div class="col-3 align-items-start">
                <p>Mã lớp: {{ $schedule->MaLop }}</p>
                <div>
                    <span>Ver: {{ $chuongtrinh->PhienBan }}</span>
                    <span>{{ \Carbon\Carbon::parse($chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="col-4 text-start">
                <p class="m-0">Bắt đầu học từ ngày: {{  \Carbon\Carbon::parse($schedule->NgayHoc)->format('d/m/Y')}}</p>
                <p class="m-0">Học Lý thuyết tại phòng: {{ $phonglt->TenPhong ?? ' Chưa có ' }}</p>
                <p class="m-0">Học Thực hành tại phòng:  {{ $phongth->TenPhong ?? ' Chưa có ' }}</p>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <td>Ngày</td>
                    <td>Tuần</td>
                    <td>Giờ học</td>
                    <td>THỨ HAI</td>
                    <td>THỨ BA</td>
                    <td>THỨ TƯ</td>
                    <td>THỨ NĂM</td>
                    <td>THỨ SÁU</td>
                </tr>
            </thead>
            <tbody>
                @php
                use Carbon\Carbon;

                // Ngày bắt đầu học
                $startDate = Carbon::parse($schedule->NgayHoc);
                $totalHours = $hocki->TongGioTrienKhai;
                $emptyDays = 0;

                // Xác định ngày đầu tuần (Thứ 2) của tuần chứa ngày bắt đầu học
                $weekStartDate = $startDate->copy()->startOfWeek();

                // Đếm số ngày trống trước ngày bắt đầu học trong tuần đó (không tính thứ 7 và Chủ nhật)
                for ($date = $weekStartDate; $date->lt($startDate); $date->addDay()) {
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
                        'remaining' => $monhoc->GioTrienKhai
                    ];

                    // Nếu là môn học cuối cùng, đánh dấu bằng chỉ số
                    if ($index === $subjectCount - 1) {
                        $subjectOccurrences[$monhoc->TenMH]['lastSubject'] = true;
                    }
                }
                // Các ngày trong tuần
                $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU'];

                // Hàm thêm ngày bỏ qua cuối tuần
                $addDaysSkippingWeekends = function($date, $days) {
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
                };

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
                $getSubjectForDay = function(&$subjectOccurrences, $currentDate, &$totalHours, &$examDays, &$selfStudyDays, $addDaysSkippingWeekends, $holidayDates) {
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

                                    // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                    while (isset($holidayDates[$examDate->format('Y-m-d')])) {
                                        $examDate->addDay();
                                    }

                                    // Đặt tên là "self-study" cho các ngày trống trước ngày thi
                                    $emptyDays = $currentDate->diffInDays($examDate) - 1;
                                    for ($i = 0; $i < $emptyDays; $i++) {
                                        $selfStudyDate = $currentDate->copy()->addDays($i + 1);
                                        if ($selfStudyDate->dayOfWeek !== Carbon::SATURDAY && $selfStudyDate->dayOfWeek !== Carbon::SUNDAY && !isset($holidayDates[$selfStudyDate->format('Y-m-d')])) {
                                            $examDays[$selfStudyDate->format('Y-m-d')] = "self-study";
                                            $totalHours += 2;
                                        }
                                    }
                                } else {
                                    // Xử lý các môn học khác
                                    $examDate = $addDaysSkippingWeekends(clone $currentDate, 5);

                                    // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                    while (isset($holidayDates[$examDate->format('Y-m-d')])) {
                                        $examDate->addDay();
                                    }

                                    // Nếu ngày thi vào thứ hai thì không có ngày self-study
                                    if ($examDate->dayOfWeek !== Carbon::MONDAY) {
                                        $selfStudyDate = $examDate->copy()->subDay();
                                        // Nếu ngày self-study không rơi vào thứ 7 hoặc Chủ nhật
                                        if ($selfStudyDate->dayOfWeek !== Carbon::SATURDAY && $selfStudyDate->dayOfWeek !== Carbon::SUNDAY && !isset($holidayDates[$selfStudyDate->format('Y-m-d')])) {
                                            $examDays[$selfStudyDate->format('Y-m-d')] = "self-study";
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

                                $examDays[$examDate->format('Y-m-d')] = "Thi $subject";
                                $totalHours += 2;
                            }
                            return $subject;
                        }
                    }
                    return '';
                };

                // Tạo lịch học
                $scheduleMatrix = [];
                $examDays = [];
                for ($week = 1; $week <= $totalWeeks; $week++) {
                    $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
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
                                if ($subject === "self-study") {
                                    $style = 'text-dark';
                                }
                            } else if (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                                $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                                $style = 'color: green; font-weight: bold;';
                            } else {
                                if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                                    $subject = $holidayDates[$currentDate->format('Y-m-d')];
                                    $style = "background-color: yellow;";
                                } else {
                                    $subject = $getSubjectForDay($subjectOccurrences, $currentDate, $totalHours, $examDays, $selfStudyDays, $addDaysSkippingWeekends, $holidayDates);

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

                @foreach ($scheduleMatrix as $week => $days)
                    @php
                    $weekDates = collect($days)->pluck('date')->toArray();
                    @endphp
                    <tr>
                        <th>{{ implode(' - ', [$weekDates[0], end($weekDates)]) }}</th>
                        <th class="text-wrap align-middle">{{ $week }}</th>
                        <th class="text-wrap align-middle" style="width: 12rem;">{{ $dsmh->TenKhungGio ?? ''}}</th>

                        @foreach ($days as $dayData)
                            <td class="text-wrap align-middle" style="width: 12rem; {{ $dayData['style'] }}">
                                {{ $dayData['subject'] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <form id="deleteScheduleForm" action="{{ route('deleteSchedule', ['TenTKB' => $schedule->TenTKB]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Xóa</button>
        </form>
        <div class="ms-1">
            <a href="{{ route('exportExcel', $schedule->TenTKB) }}" class="btn btn-primary">Xuất</a>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 start-0 m-3">
    <div class="mb-2">
        <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addTimeSlotButton" data-bs-toggle="modal" data-bs-target="#timeSlotModal">
            <i class="fas fa-clock"></i>
        </button>
    </div>
    <div class="mb-2" >
      <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addAbsenceButton" data-bs-toggle="modal" data-bs-target="#absenceModal">
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <div class="mb-2" >
      <button type="button" class="btn btn-primary btn-lg rounded-circle" id="addSelfStudy" data-bs-toggle="modal" data-bs-target="#SelfStudyModal">
        <i class="fa-brands fa-leanpub"></i>
      </button>
    </div>
    <div class="mb-2" >
      <button type="button" class="btn btn-primary btn-lg rounded-circle" id="editTKB" data-bs-toggle="modal" data-bs-target="#EditTKBModal">
        <i class="fa-regular fa-calendar"></i>
      </button>
    </div>
  </div>

  <!-- Absence Modal -->
  <div class="modal fade" id="absenceModal" tabindex="-1" aria-labelledby="absenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="absenceModalLabel">Thêm Ngày Nghỉ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="absenceForm" method="POST" action="{{ route('saveholiday', ['TenTKB' => $schedule->TenTKB]) }}">
            @csrf
            <div class="mb-3">
              <label for="TenNgayNghi" class="form-label">Tên ngày nghỉ:</label>
              <textarea class="form-control" id="TenNgayNghi" name="TenNgayNghi" rows="1" required></textarea>
            </div>
            <div class="mb-3">
              <label for="NgayBDNghi" class="form-label">Ngày bắt đầu nghỉ :</label>
              <input type="date" class="form-control" id="NgayBDNghi" name="NgayBDNghi" required>
            </div>
            <div class="mb-3">
              <label for="NgayKT" class="form-label">Ngày kết thúc :</label>
              <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary" id="saveAbsenceButton">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Time Slot Modal -->
  <div class="modal fade" id="timeSlotModal" tabindex="-1" aria-labelledby="timeSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="timeSlotModalLabel">Thêm Khung Giờ Học</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="timeSlotForm" method="POST" action="{{ route('saveTimeSlot', ['TenTKB' => $schedule->TenTKB]) }}">
            @csrf
            <div class="mb-3">
              <label for="khunggio" class="form-label">Tên khung giờ</label>
              <select id="khunggio" class="form-select @error('khunggio') is-invalid @enderror" name="khunggio">
                <option value="">----- Tên khung giờ -----</option>
                @foreach($khunggio as $kg)
                  <option value="{{ $kg->TenKhungGio }}">{{ $kg->TenKhungGio }}</option>
                @endforeach
              </select>
              @error ('khunggio')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary" id="saveTimeSlotButton">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="SelfStudyModal" tabindex="-1" aria-labelledby="SelfStudyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="SelfStudyModalLabel">Thêm ngày tự học</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="SelfStudyForm" method="POST" action="{{ route('saveSelfStudy', ['TenTKB' => $schedule->TenTKB]) }}">
            @csrf
            <div class="mb-3">
              <label for="ngaytuhoc" class="form-label">Tên Ngày Tự Học</label>
              <select id="ngaytuhoc" class="form-select @error('ngaytuhoc') is-invalid @enderror" name="ngaytuhoc">
                <option value="">----- Tên Ngày Tự Học  -----</option>
                <option value="Self Study">Self Study</option>
                <option value="Team works">Team Works</option>
              </select>

            </div>
            <div class="mb-3">
              <label for="NgayBDTuHoc" class="form-label">Ngày bắt đầu tự học :</label>
              <input type="date" class="form-control" id="NgayBDTuHoc" name="NgayBDTuHoc" required>
            </div>
            <div class="mb-3">
              <label for="NgayKTTuHoc" class="form-label">Ngày kết thúc tự học :</label>
              <input type="date" class="form-control" id="NgayKTTuHoc" name="NgayKTTuHoc" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary" id="saveSelfStudyButton">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="EditTKBModal" tabindex="-1" aria-labelledby="EditTKBModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="EditTKBModalLabel">Chỉnh sửa thời gian khai giảng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="EditTKBForm" method="POST" action="{{ route('EditTKB', ['TenTKB' => $schedule->TenTKB]) }}">
            @csrf
            <div class="mb-3">
                    <label for="NgayHoc" class="form-label">Ngày bắt đầu học</label>
                    <input type="date" class="form-control @error('NgayHoc') is-invalid @enderror" id="NgayHoc" name="NgayHoc">
                    @error ('NgayHoc')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary" id="saveSelfStudyButton">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


<script>
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này?')) {
            document.getElementById('deleteScheduleForm').submit();
        }
    }
</script>

@endsection
