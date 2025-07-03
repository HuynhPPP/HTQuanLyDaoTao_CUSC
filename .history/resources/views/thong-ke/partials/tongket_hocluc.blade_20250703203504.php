@php
    // Đảm bảo thứ tự xếp loại cố định
    $xepLoaiOrder = ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'];
    
    // Tạo mảng dữ liệu với thứ tự cố định
    $xepLoaiData = collect($xepLoaiOrder)
        ->mapWithKeys(fn($loai) => [$loai => collect($tongKet)
            ->filter(fn($sv) => $sv['XepLoai'] === $loai)
            ->count()
        ])
        ->filter(fn($count) => $count > 0); // Chỉ giữ các loại có sinh viên

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
                                    $bgColor = match ($sv['XepLoai']) {
                                        'Xuất sắc' => 'success',
                                        'Giỏi' => 'primary',
                                        'Khá' => 'info',
                                        'Trung bình' => 'warning',
                                        default => 'danger',
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
        // Kiểm tra dữ liệu trước khi vẽ biểu đồ
        const xepLoaiLabels = {!! json_encode($xepLoaiLabels) !!};
        const xepLoaiValues = {!! json_encode($xepLoaiValues) !!};
        
        console.log('Xếp loại Labels:', xepLoaiLabels);
        console.log('Xếp loại Values:', xepLoaiValues);

        // Màu sắc tương ứng với từng xếp loại
        const backgroundColor = {
            'Xuất sắc': 'rgba(40, 167, 69, 0.6)',
            'Giỏi': 'rgba(0, 123, 255, 0.6)',
            'Khá': 'rgba(23, 162, 184, 0.6)',
            'Trung bình': 'rgba(255, 193, 7, 0.6)',
            'Yếu': 'rgba(220, 53, 69, 0.6)'
        };

        // Chọn màu dựa trên nhãn
        const chartBackgroundColors = xepLoaiLabels.map(label => backgroundColor[label]);

        // Vẽ biểu đồ
        new Chart(document.getElementById('chart-xeploai'), {
            type: 'pie',
            data: {
                labels: xepLoaiLabels,
                datasets: [{
                    label: 'Số lượng sinh viên',
                    data: xepLoaiValues,
                    backgroundColor: chartBackgroundColors
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
