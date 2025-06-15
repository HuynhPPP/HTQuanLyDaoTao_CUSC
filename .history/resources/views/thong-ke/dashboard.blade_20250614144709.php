@extends('thong-ke.layout')

@section('thong-ke-content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Tổng Sinh Viên</h4>
                </div>
                <div class="card-body">
                    {{ $tongSinhVien }}
                </div>
                <div class="card-footer text-muted">
                    Nam: {{ $tongNam }} | Nữ: {{ $tongNu }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Điểm Trung Bình</h4>
                </div>
                <div class="card-body">
                    {{ $diemTrungBinh }}
                </div>
                <div class="card-footer text-muted">
                    Tỷ Lệ Đạt: {{ $tyLeDat }}%
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Học Lực</h4>
                </div>
                <div class="card-body">
                    @foreach($hocLucPhanLoai as $hocLuc => $soLuong)
                        {{ $hocLuc }}: {{ $soLuong }}<br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Khóa Học</h4>
                </div>
                <div class="card-body">
                    {{ $tongKhoaHoc }}
                </div>
                <div class="card-footer text-muted">
                    Điểm TB: {{ $diemTrungBinhKhoaHoc }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Phân Bổ Học Lực</h4>
            </div>
            <div class="card-body">
                <canvas id="hocLucChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Thống Kê Sinh Viên</h4>
            </div>
            <div class="card-body">
                <canvas id="sinhVienChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ Học Lực
    const hocLucCtx = document.getElementById('hocLucChart').getContext('2d');
    const hocLucLabels = {!! json_encode(array_keys($hocLucPhanLoai)) !!};
    const hocLucData = {!! json_encode(array_values($hocLucPhanLoai)) !!};

    new Chart(hocLucCtx, {
        type: 'pie',
        data: {
            labels: hocLucLabels,
            datasets: [{
                data: hocLucData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Phân Bổ Học Lực'
            }
        }
    });

    // Biểu đồ Sinh Viên
    const sinhVienCtx = document.getElementById('sinhVienChart').getContext('2d');
    
    new Chart(sinhVienCtx, {
        type: 'doughnut',
        data: {
            labels: ['Nam', 'Nữ'],
            datasets: [{
                data: [{{ $tongNam }}, {{ $tongNu }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Tỷ Lệ Giới Tính Sinh Viên'
            }
        }
    });
});
</script>
@endpush 