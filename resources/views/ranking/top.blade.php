@extends('layouts.new_app.master')

@section('page-title', 'Sinh Viên Có Thành Tích Xuất Sắc')

@section('main-content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Sinh viên có thành tích xuất sắc</h1>
            <div class="section-header-breadcrumb mb-0">
                <a href="{{ route('ranking.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="section-body">
            <!-- Thông tin tổng quan -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-info-circle"></i> Thông tin tổng quan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                        <h5>{{ $topSinhVien->count() }}</h5>
                                        <p class="text-muted mb-0">Sinh viên được đánh giá</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                        <h5>{{ number_format($topSinhVien->max('DiemTB'), 2) }}</h5>
                                        <p class="text-muted mb-0">Điểm cao nhất</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                        <h5>{{ number_format($topSinhVien->avg('DiemTB'), 2) }}</h5>
                                        <p class="text-muted mb-0">Điểm trung bình</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-graduation-cap fa-2x text-success mb-2"></i>
                                        <h5>{{ $topSinhVien->where('XepLoai', 'Xuất sắc')->count() }}</h5>
                                        <p class="text-muted mb-0">Sinh viên xuất sắc</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng danh sách sinh viên xuất sắc -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-list-alt"></i> Danh sách sinh viên có thành tích xuất sắc</h4>
                        </div>
                        <div class="card-body">
                            @if($topSinhVien->count() > 0)
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topSinhVien as $index => $sv)
                                            <tr class="@if($index < 3) table-warning @endif">
                                                <td>
                                                    @if($index == 0)
                                                        <span class="badge badge-warning badge-lg">1</span>
                                                    @elseif($index == 1)
                                                        <span class="badge badge-secondary badge-lg">2</span>
                                                    @elseif($index == 2)
                                                        <span class="badge badge-warning badge-lg">3</span>
                                                    @else
                                                        <span class="badge badge-info">{{ $index + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $sv->MaSV }}</strong></td>
                                                <td>{{ $sv->sinhVien->HoTen ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge badge-lg">{{ number_format($sv->DiemTB, 2) }}</span>
                                                </td>
                                                <td>{{ $sv->SoMon }}</td>
                                                <td>
                                                    @switch($sv->XepLoai)
                                                        @case('Xuất sắc')
                                                            <span>Xuất sắc</span>
                                                            @break
                                                        @case('Giỏi')
                                                            <span>Giỏi</span>
                                                            @break
                                                        @case('Khá')
                                                            <span>Khá</span>
                                                            @break
                                                        @case('Trung bình')
                                                            <span>Trung bình</span>
                                                            @break
                                                        @default
                                                            <span>Yếu</span>
                                                    @endswitch
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
                                    <p class="mb-0">Chưa có dữ liệu để đánh giá sinh viên xuất sắc. Vui lòng kiểm tra lại sau.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân tích thống kê -->
            @if($topSinhVien->count() > 0)
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-bar"></i> Phân bố điểm số</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartDiemSo" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-table"></i> Thống kê chi tiết</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold">Tổng số sinh viên:</td>
                                            <td class="text-right">{{ $topSinhVien->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Điểm TB cao nhất:</td>
                                            <td class="text-right"><span>{{ number_format($topSinhVien->max('DiemTB'), 2) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Điểm TB thấp nhất:</td>
                                            <td class="text-right"><span>{{ number_format($topSinhVien->min('DiemTB'), 2) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Điểm TB trung bình:</td>
                                            <td class="text-right"><span>{{ number_format($topSinhVien->avg('DiemTB'), 2) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Sinh viên xuất sắc:</td>
                                            <td class="text-right">{{ $topSinhVien->where('XepLoai', 'Xuất sắc')->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Sinh viên giỏi:</td>
                                            <td class="text-right">{{ $topSinhVien->where('XepLoai', 'Giỏi')->count() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ phân bố điểm số
        var ctxDiemSo = document.getElementById('chartDiemSo').getContext('2d');
        new Chart(ctxDiemSo, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topSinhVien as $sv)
                        "{{ $sv->MaSV }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Điểm TB',
                    data: [
                        @foreach($topSinhVien as $sv)
                            {{ $sv->DiemTB }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($topSinhVien as $index => $sv)
                            @if($index < 3)
                                'rgba(255, 193, 7, 0.8)',
                            @else
                                'rgba(54, 162, 235, 0.8)',
                            @endif
                        @endforeach
                    ],
                    borderColor: [
                        @foreach($topSinhVien as $index => $sv)
                            @if($index < 3)
                                'rgba(255, 193, 7, 1)',
                            @else
                                'rgba(54, 162, 235, 1)',
                            @endif
                        @endforeach
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
