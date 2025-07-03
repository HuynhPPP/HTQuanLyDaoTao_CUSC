@php
    $datMonLabels = array_map(fn($m) => \App\Models\MonHoc::find($m)->TenMH ?? $m, array_keys($thongKeDat->toArray()));
    $datData = $thongKeDat->pluck('dat')->values();
    $khongDatData = $thongKeDat->pluck('khongDat')->values();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Thống kê đạt/chưa đạt theo môn học</h4>
            </div>
            <div class="card-body">
                <canvas id="chart-datmon" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Chi tiết thống kê</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
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
                                    $bgColor = $tyLe >= 50 ? 'success' : 'danger';
                                @endphp
                                <tr>
                                    <td>{{ $tenMH }}</td>
                                    <td>{{ $stats['dat'] }}</td>
                                    <td>{{ $stats['khongDat'] }}</td>
                                    <td>{{ $stats['tong'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $bgColor }}">
                                            {{ $tyLe }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom-js')
    <script>
        new Chart(document.getElementById('chart-datmon'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($datMonLabels) !!},
                datasets: [{
                    label: 'Đạt',
                    data: {!! json_encode($datData) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.6)'
                },
                {
                    label: 'Không đạt',
                    data: {!! json_encode($khongDatData) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.6)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng sinh viên'
                        }
                    }
                }
            }
        });
    </script>
@endsection
