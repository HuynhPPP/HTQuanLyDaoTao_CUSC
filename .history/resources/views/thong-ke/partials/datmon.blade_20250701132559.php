<canvas id="chart-datmon" height="120"></canvas>
<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Môn học</th>
            <th>Đạt</th>
            <th>Không đạt</th>
            <th>Tổng</th>
            <th>Tỷ lệ đạt</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($thongKeDat as $maMH => $stats)
            @php
                $tenMH = \App\Models\MonHoc::find($maMH)->TenMH ?? $maMH;
                $tyLe = $stats['tong'] > 0 ? round(($stats['dat'] / $stats['tong']) * 100, 1) : 0;
            @endphp
            <tr>
                <td>{{ $tenMH }}</td>
                <td>{{ $stats['dat'] }}</td>
                <td>{{ $stats['khongDat'] }}</td>
                <td>{{ $stats['tong'] }}</td>
                <td>{{ $tyLe }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('custom-js')
    <script>
        const datMonLabels = {!! json_encode(
            array_map(fn($m) => \App\Models\MonHoc::find($m)->TenMH ?? $m, array_keys($thongKeDat->toArray())),
        ) !!};
        const datData = {!! json_encode($thongKeDat->pluck('dat')->values()) !!};
        const khongDatData = {!! json_encode($thongKeDat->pluck('khongDat')->values()) !!};

        new Chart(document.getElementById('chart-datmon'), {
            type: 'bar',
            data: {
                labels: datMonLabels,
                datasets: [{
                        label: 'Đạt',
                        data: datData,
                        backgroundColor: 'rgba(40, 167, 69, 0.6)'
                    },
                    {
                        label: 'Không đạt',
                        data: khongDatData,
                        backgroundColor: 'rgba(220, 53, 69, 0.6)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Tỷ lệ đạt/chưa đạt theo môn học'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
