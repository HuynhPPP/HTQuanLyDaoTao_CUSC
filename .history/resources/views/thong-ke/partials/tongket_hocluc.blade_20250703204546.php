@php
    // Đảm bảo thứ tự xếp loại cố định
    $xepLoaiOrder = ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu', 'Kém'];

    // Đếm số lượng sinh viên theo từng xếp loại
    $xepLoaiCounts = collect($tongKet ?? [])
        ->groupBy('XepLoai')
        ->map->count()
        ->toArray();

    // Sắp xếp và lọc các xếp loại
    $xepLoaiData = collect($xepLoaiOrder)
        ->mapWithKeys(
            fn($loai) => [
                $loai => $xepLoaiCounts[$loai] ?? 0,
            ],
        )
        ->filter(fn($count) => $count > 0);

    $xepLoaiLabels = $xepLoaiData->keys()->toArray();
    $xepLoaiValues = $xepLoaiData->values()->toArray();

    // Debug: In ra thông tin để kiểm tra
    \Log::info('Xếp loại Labels:', $xepLoaiLabels);
    \Log::info('Xếp loại Values:', $xepLoaiValues);
@endphp

<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Phân loại học lực</h4>
            </div>
            <div class="card-body">
                <canvas id="chart-xeploai" height="300"></canvas>

                <!-- Debug thông tin -->
                <div id="chart-debug" class="mt-3">
                    <strong>Debug Thông Tin:</strong>
                    <pre id="chart-debug-info"></pre>
                </div>
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
                            @foreach ($tongKet ?? [] as $sv)
                                @php
                                    $bgColor = match ($sv['XepLoai']) {
                                        'Xuất sắc' => 'success',
                                        'Giỏi' => 'primary',
                                        'Khá' => 'info',
                                        'Trung bình' => 'warning',
                                        'Yếu' => 'danger',
                                        'Kém' => 'danger',
                                        default => 'secondary',
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
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra dữ liệu trước khi vẽ biểu đồ
            const xepLoaiLabels = {!! json_encode($xepLoaiLabels) !!};
            const xepLoaiValues = {!! json_encode($xepLoaiValues) !!};

            // Hiển thị debug info
            document.getElementById('chart-debug-info').textContent =
                `Labels: ${JSON.stringify(xepLoaiLabels)}\nValues: ${JSON.stringify(xepLoaiValues)}`;

            console.log('Xếp loại Labels:', xepLoaiLabels);
            console.log('Xếp loại Values:', xepLoaiValues);

            // Kiểm tra xem Chart.js đã được nạp chưa
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                document.getElementById('chart-debug-info').textContent +=
                    '\n\nLỖI: Chart.js chưa được nạp';
                return;
            }

            // Màu sắc tương ứng với từng xếp loại
            const backgroundColor = {
                'Xuất sắc': 'rgba(40, 167, 69, 0.6)', // Xanh lá
                'Giỏi': 'rgba(0, 123, 255, 0.6)', // Xanh dương
                'Khá': 'rgba(23, 162, 184, 0.6)', // Xanh cyan
                'Trung bình': 'rgba(255, 193, 7, 0.6)', // Vàng
                'Yếu': 'rgba(220, 53, 69, 0.6)', // Đỏ
                'Kém': 'rgba(108, 117, 125, 0.6)' // Xám
            };

            // Chọn màu dựa trên nhãn
            const chartBackgroundColors = xepLoaiLabels.map(label => backgroundColor[label]);

            // Vẽ biểu đồ
            if (xepLoaiLabels.length > 0) {
                const ctx = document.getElementById('chart-xeploai');
                try {
                    new Chart(ctx, {
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
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b,
                                                0);
                                            const value = context.parsed;
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return `${context.label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Lỗi khi vẽ biểu đồ:', error);
                    document.getElementById('chart-debug-info').textContent +=
                        `\n\nLỖI VẼ BIỂU ĐỒ: ${error.message}`;
                }
            } else {
                // Nếu không có dữ liệu, hiển thị thông báo
                const ctx = document.getElementById('chart-xeploai');
                ctx.innerHTML = '<p class="text-center">Không có dữ liệu để hiển thị</p>';
            }
        });
    </script>
@endsection
