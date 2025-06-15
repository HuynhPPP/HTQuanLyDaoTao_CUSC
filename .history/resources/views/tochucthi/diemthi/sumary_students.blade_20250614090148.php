@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Thống kê sinh viên Đạt/Không Đạt - {{ $chuongTrinh->TenChuongTrinh }}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Chi Tiết Thống Kê Điểm Môn Học</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-thongke">
                    <thead>
                        <tr>
                            <th>Mã Môn</th>
                            <th>Tên Môn Học</th>
                            <th>Tổng Số Sinh Viên</th>
                            <th>Sinh Viên Đạt</th>
                            <th>Sinh Viên Không Đạt</th>
                            <th>Tỷ Lệ Đạt (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($thongKeDiemMon as $monHoc)
                        <tr>
                            <td>{{ $monHoc['MaMH'] }}</td>
                            <td>{{ $monHoc['TenMH'] }}</td>
                            <td>{{ $monHoc['TongSinhVien'] }}</td>
                            <td>{{ $monHoc['SinhVienDat'] }}</td>
                            <td>{{ $monHoc['SinhVienKhongDat'] }}</td>
                            <td>
                                {{ $monHoc['TongSinhVien'] > 0 
                                    ? number_format(($monHoc['SinhVienDat'] / $monHoc['TongSinhVien']) * 100, 2) 
                                    : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Biểu đồ thống kê -->
    <div class="card">
        <div class="card-header">
            <h4>Biểu đồ thống kê</h4>
        </div>
        <div class="card-body">
            <canvas id="chartThongKeDiem"></canvas>
        </div>
    </div>
</div>

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('chartThongKeDiem').getContext('2d');
    var monHoc = @json($thongKeDiemMon);

    var labels = monHoc.map(function(mon) { return mon.TenMH; });
    var dataDat = monHoc.map(function(mon) { return mon.SinhVienDat; });
    var dataKhongDat = monHoc.map(function(mon) { return mon.SinhVienKhongDat; });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Sinh Viên Đạt',
                    data: dataDat,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'Sinh Viên Không Đạt',
                    data: dataKhongDat,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Thống Kê Sinh Viên Đạt/Không Đạt Theo Môn Học'
                }
            }
        }
    });
});
</script>
@endsection
@endsection