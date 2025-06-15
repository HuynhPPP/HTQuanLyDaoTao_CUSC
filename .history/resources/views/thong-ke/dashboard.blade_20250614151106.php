@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Bảng thống kê</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Trang Chủ</a></div>
                <div class="breadcrumb-item">Thống Kê</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Số lượng sinh viên</h4>
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
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Số lượng sinh viên</h4>
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
                            <i class="fas fa-chart-line"></i>
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
                                @foreach ($hocLucPhanLoai as $hocLuc => $soLuong)
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
                            <h4>Biểu Đồ Học Lực</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="hocLucChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu Đồ Sinh Viên Theo Giới Tính</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="gioiTinhChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chuyển đổi Collection sang mảng
            var hocLucPhanLoai = {!! json_encode($hocLucPhanLoai->toArray()) !!};

            // Biểu đồ học lực
            var hocLucCtx = document.getElementById('hocLucChart').getContext('2d');
            var hocLucData = {
                labels: Object.keys(hocLucPhanLoai),
                datasets: [{
                    label: 'Học Lực',
                    data: Object.values(hocLucPhanLoai),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ]
                }]
            };
            new Chart(hocLucCtx, {
                type: 'pie',
                data: hocLucData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Phân Loại Học Lực'
                    }
                }
            });

            // Biểu đồ giới tính
            var gioiTinhCtx = document.getElementById('gioiTinhChart').getContext('2d');
            var gioiTinhData = {
                labels: ['Nam', 'Nữ'],
                datasets: [{
                    label: 'Sinh Viên Theo Giới Tính',
                    data: [{{ $tongNam }}, {{ $tongNu }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            };
            new Chart(gioiTinhCtx, {
                type: 'doughnut',
                data: gioiTinhData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Tỷ Lệ Sinh Viên Nam/Nữ'
                    }
                }
            });
        });
    </script>
@endsection
