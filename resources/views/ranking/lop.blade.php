@extends('layouts.new_app.master')

@section('page-title', 'Kết Quả Học Tập - Lớp ' . $lop->TenLop)

@section('main-content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Kết Quả Học Tập - Lớp {{ $lop->TenLop }}</h1>
            <div class="section-header-breadcrumb mb-0">
                <a href="{{ route('ranking.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="section-body">
            <!-- Thông tin lớp học -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="fas fa-info-circle"></i> Thông tin lớp học</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="font-weight-bold">Mã lớp:</td>
                                            <td>{{ $lop->MaLop }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Tên lớp:</td>
                                            <td>{{ $lop->TenLop }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="font-weight-bold">Chương trình:</td>
                                            <td>{{ $lop->loaidaotao->TenChuongTrinh ?? 'Chưa xác định' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Ngày bắt đầu:</td>
                                            <td>{{ $lop->NgayBatDau ? \Carbon\Carbon::parse($lop->NgayBatDau)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng sinh viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['tong_sinh_vien'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Xuất sắc</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['xuat_sac'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Giỏi</h4>
                            </div>
                            <div class="card-body">
                                {{ $thongKe['gioi'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Điểm TB lớp</h4>
                            </div>
                            <div class="card-body">
                                {{ number_format($thongKe['diem_tb_lop'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân tích kết quả học tập -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h4><i class="fas fa-chart-pie"></i> Phân bố xếp loại học lực</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartXepLoai" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h4><i class="fas fa-table"></i> Thống kê chi tiết</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Xếp loại</th>
                                            <th>Số lượng</th>
                                            <th>Tỷ lệ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge badge-success">Xuất sắc</span></td>
                                            <td class="font-weight-bold">{{ $thongKe['xuat_sac'] }}</td>
                                            <td>{{ $thongKe['tong_sinh_vien'] > 0 ? round($thongKe['xuat_sac'] / $thongKe['tong_sinh_vien'] * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge badge-info">Giỏi</span></td>
                                            <td class="font-weight-bold">{{ $thongKe['gioi'] }}</td>
                                            <td>{{ $thongKe['tong_sinh_vien'] > 0 ? round($thongKe['gioi'] / $thongKe['tong_sinh_vien'] * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge badge-primary">Khá</span></td>
                                            <td class="font-weight-bold">{{ $thongKe['kha'] }}</td>
                                            <td>{{ $thongKe['tong_sinh_vien'] > 0 ? round($thongKe['kha'] / $thongKe['tong_sinh_vien'] * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge badge-warning">Trung bình</span></td>
                                            <td class="font-weight-bold">{{ $thongKe['trung_binh'] }}</td>
                                            <td>{{ $thongKe['tong_sinh_vien'] > 0 ? round($thongKe['trung_binh'] / $thongKe['tong_sinh_vien'] * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge badge-danger">Yếu</span></td>
                                            <td class="font-weight-bold">{{ $thongKe['yeu'] }}</td>
                                            <td>{{ $thongKe['tong_sinh_vien'] > 0 ? round($thongKe['yeu'] / $thongKe['tong_sinh_vien'] * 100, 1) : 0 }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng kết quả học tập -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="fas fa-list-alt"></i> Bảng kết quả học tập sinh viên</h4>
                        </div>
                        <div class="card-body">
                            @if($bangXepHang->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Thứ hạng</th>
                                                <th>Mã sinh viên</th>
                                                <th>Họ và tên</th>
                                                <th>Điểm trung bình</th>
                                                <th>Số môn học</th>
                                                <th>Xếp loại</th>
                                                <th>Đánh giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bangXepHang as $index => $sv)
                                            <tr>
                                                <td>
                                                    @if($index < 3)
                                                        <span class="badge badge-warning badge-lg">{{ $index + 1 }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $sv['MaSV'] }}</strong></td>
                                                <td>{{ $sv['HoTen'] }}</td>
                                                <td>
                                                    <span class="badge badge-primary badge-lg">{{ $sv['DiemTB'] }}</span>
                                                </td>
                                                <td>{{ $sv['SoMonCoDiem'] }}</td>
                                                <td>
                                                    @switch($sv['XepLoai'])
                                                        @case('Xuất sắc')
                                                            <span class="badge badge-success">Xuất sắc</span>
                                                            @break
                                                        @case('Giỏi')
                                                            <span class="badge badge-info">Giỏi</span>
                                                            @break
                                                        @case('Khá')
                                                            <span class="badge badge-primary">Khá</span>
                                                            @break
                                                        @case('Trung bình')
                                                            <span class="badge badge-warning">Trung bình</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-danger">Yếu</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($sv['DiemTB'] >= 8.5)
                                                        <i class="fas fa-star text-warning"></i> Xuất sắc
                                                    @elseif($sv['DiemTB'] >= 7.0)
                                                        <i class="fas fa-graduation-cap text-info"></i> Giỏi
                                                    @elseif($sv['DiemTB'] >= 5.5)
                                                        <i class="fas fa-thumbs-up text-primary"></i> Khá
                                                    @elseif($sv['DiemTB'] >= 4.0)
                                                        <i class="fas fa-check text-warning"></i> Trung bình
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-danger"></i> Cần cải thiện
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>Chưa có dữ liệu</h5>
                                    <p class="mb-0">Chưa có dữ liệu điểm để đánh giá kết quả học tập. Vui lòng kiểm tra lại sau.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ phân bố xếp loại
        var ctxXepLoai = document.getElementById('chartXepLoai').getContext('2d');
        new Chart(ctxXepLoai, {
            type: 'doughnut',
            data: {
                labels: ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'],
                datasets: [{
                    data: [
                        {{ $thongKe['xuat_sac'] }},
                        {{ $thongKe['gioi'] }},
                        {{ $thongKe['kha'] }},
                        {{ $thongKe['trung_binh'] }},
                        {{ $thongKe['yeu'] }}
                    ],
                    backgroundColor: [
                        '#28a745', '#17a2b8', '#007bff', '#ffc107', '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
