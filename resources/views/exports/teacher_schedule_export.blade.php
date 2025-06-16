<!DOCTYPE html>
<html>

<head>
    <title>Lịch Giảng Viên - {{ $schedule->TenTKB }}</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .teacher-name {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</h2>
        <h1>LỊCH GIẢNG DẠY LỚP {{ $schedule->MaLop }} - {{ $chuongTrinhName }}</h1>
        <h3>HỌC KỲ {{ $hocki->TenHK }}</h3>
        <p><strong>Bắt đầu học từ ngày:</strong> {{ $startDate->format('d/m/Y') }}</p>
        <p><strong>Phòng lý thuyết:</strong> {{ $phonglt->TenPhong ?? 'Chưa có' }}</p>
        <p><strong>Phòng thực hành:</strong> {{ $phongth->TenPhong ?? 'Chưa có' }}</p>
    </div>

    @foreach ($teacherSchedules as $maGV => $teacherData)
        <div class="teacher-name">Giảng viên: {{ $teacherData['info']->HoTenGV }}</div>
        <table>
            <thead>
                <tr>
                    <th>NGÀY</th>
                    <th>TUẦN</th>
                    @foreach ($weekDays as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($teacherData['schedule'] as $week => $days)
                    @php
                        $weekDates = collect($days)->pluck('date')->toArray();
                    @endphp
                    <tr>
                        <td>{{ implode(' - ', [reset($weekDates), end($weekDates)]) }}</td>
                        <td>{{ $week }}</td>
                        @foreach ($days as $dayData)
                            <td>{{ $dayData['subject'] ?: '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endforeach
</body>

</html>
