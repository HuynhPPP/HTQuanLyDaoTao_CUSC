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
                    $startDate = Carbon::parse($schedule->NgayHoc);
                    $totalHours = $hocki->GioTrienKhai;
                    $totalWeeks = ceil($totalHours / 10);
                    $subjectOccurrences = [];

                    foreach ($monhocs as $monhoc) {
                        $subjectOccurrences[$monhoc->TenMH] = [
                            'first' => null,
                            'last' => null,
                            'remaining' => $monhoc->GioTrienKhai
                        ];
                    }

                    $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU'];

                    function getSubjectForDay(&$subjectOccurrences, $currentDate) {
                        foreach ($subjectOccurrences as $subject => &$details) {
                            if ($details['remaining'] > 0) {
                                if (is_null($details['first'])) {
                                    $details['first'] = $currentDate;
                                }
                                $details['remaining'] -= 2;
                                if ($details['remaining'] <= 0) {
                                    $details['last'] = $currentDate;
                                }
                                return $subject;
                            }
                        }
                        return '';
                    }
                @endphp

                @for ($week = 1; $week <= $totalWeeks; $week++)
                    @php
                        $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
                        $weekEnd = $weekStart->copy()->endOfWeek()->subDays(2);
                    @endphp
                    <tr>
                        <th>{{ $weekStart->format('d/m/Y') . ' - ' . $weekEnd->format('d/m/Y') }}</th>
                        <th>{{ $week }}</th>
                        <th>{{ $schedule->TenKhungGio }}</th>

                        @foreach ($weekDays as $day)
                            @php
                                $currentDate = $weekStart->copy()->addDays(array_search($day, $weekDays));
                                $subject = '';
                                $style = '';

                                if ($currentDate->gte($startDate)) {
                                    $subject = getSubjectForDay($subjectOccurrences, $currentDate);

                                    if ($subject) {
                                        if ($subjectOccurrences[$subject]['first'] == $currentDate) {
                                            $style = 'color: red; font-weight: bold;';
                                        } elseif ($subjectOccurrences[$subject]['last'] == $currentDate) {
                                            $style = 'color: purple; font-weight: bold;';
                                        }
                                    }
                                }
                            @endphp
                            <td class="text-wrap" style="width: 10rem; {{ $style }}">
                                {{ $subject }}
                            </td>
                        @endforeach
                    </tr>
                @endfor
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

<script>
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này?')) {
            document.getElementById('deleteScheduleForm').submit();
        }
    }
</script>

@endsection
