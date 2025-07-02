<table border="1" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold; text-align: center;">
            <th>STT</th>
            <th>Lớp</th>
            <th>Tên đề tài</th>
            <th>GV Hướng dẫn</th>
            <th>GV Phản biện</th>
            <th>Ngày báo cáo</th>
            <th>Thời gian</th>
            <th>Địa điểm</th>
        </tr>
    </thead>
    <tbody>
        @php $stt = 1; @endphp
        @foreach($reports->groupBy('class_id') as $lop => $ds)
            <tr style="background-color: #d9edf7;">
                <td colspan="8" style="font-weight: bold;">Lớp: {{ $lop }}</td>
            </tr>
            @foreach($ds as $report)
                <tr>
                    <td style="text-align: center;">{{ $stt++ }}</td>
                    <td>{{ $report->class_id }}</td>
                    <td>{{ $report->report_name }}</td>
                    <td>{{ $report->instructor->HoTenGV ?? 'N/A' }}</td>
                    <td>{{ $report->reviewer->HoTenGV ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                    <td>{{ $report->report_time_start }} - {{ $report->report_time_end }}</td>
                    <td>{{ $report->location }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
