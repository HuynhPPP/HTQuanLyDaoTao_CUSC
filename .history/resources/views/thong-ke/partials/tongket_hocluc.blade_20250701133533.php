<canvas id="chart-xeploai" height="120"></canvas>
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Mã SV</th><th>Họ tên</th><th>Điểm TB</th><th>Xếp loại</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tongKet as $sv)
            <tr>
                <td>{{ $sv['MaSV'] }}</td>
                <td>{{ $sv['HoTen'] }}</td>
                <td>{{ number_format($sv['DiemTB'], 2) }}</td>
                <td>{{ $sv['XepLoai'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@section('custom-js')
<script>
    const xepLoaiData = {!! json_encode(collect($tongKet)->groupBy('XepLoai')->map->count()) !!};

    new Chart(document.getElementById('chart-xeploai'), {
        type: 'pie',
        data: {
            labels: Object.keys(xepLoaiData),
            datasets: [{
                label: 'Số lượng',
                data: Object.values(xepLoaiData),
                backgroundColor: [
                    '#28a745', '#ffc107', '#17a2b8', '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Phân loại học lực' }
            }
        }
    });
</script>
@endsection
