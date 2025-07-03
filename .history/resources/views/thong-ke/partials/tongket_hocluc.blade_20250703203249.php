@php
    $xepLoaiData = collect($tongKet)->groupBy('XepLoai')->map->count();
    $xepLoaiLabels = $xepLoaiData->keys()->toArray();
    $xepLoaiValues = $xepLoaiData->values()->toArray();
@endphp

<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Phân loại học lực</h4>
            </div>
            <div class="card-body">
                <canvas id="chart-xeploai" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Chi tiết học lực sinh viên</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Mã SV</th>
                                <th>Họ tên</th>
                                <th>Điểm TB</th>
                                <th>Xếp loại</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tongKet as $sv)
                                @php
                                    $bgColor = match($sv['XepLoai']) {
                                        'Xuất sắc' => 'success',
                                        'Giỏi' => 'primary',
                                        'Khá' => 'info',
                                        'Trung bình' => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $sv['MaSV'] }}</td>
                                    <td>{{ $sv['HoTen'] }}</td>
                                    <td>{{ number_format($sv['DiemTB'], 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $bgColor }}">
                                            {{ $sv['XepLoai'] }}
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
        new Chart(document.getElementById('chart-xeploai'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($xepLoaiLabels) !!},
                datasets: [{
                    label: 'Số lượng sinh viên',
                    data: {!! json_encode($xepLoaiValues) !!},
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.6)',   // Xuất sắc - Green
                        'rgba(0, 123, 255, 0.6)',   // Giỏi - Blue
                        'rgba(23, 162, 184, 0.6)',  // Khá - Cyan
                        'rgba(255, 193, 7, 0.6)',   // Trung bình - Yellow
                        'rgba(220, 53, 69, 0.6)'    // Yếu - Red
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
