@extends('layouts.new_app.master')

@section('page-title', 'So Sánh Hiệu Suất Học Tập')

@section('main-content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">So Sánh Hiệu Suất Học Tập</h1>
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
                                        <h5>{{ $soSanhLop->count() }}</h5>
                                        <p class="text-muted mb-0">Lớp được đánh giá</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                                        <h5>{{ number_format($soSanhLop->max('DiemTBLop'), 2) }}</h5>
                                        <p class="text-muted mb-0">Điểm TB cao nhất</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                        <h5>{{ number_format($soSanhLop->avg('TyLeXuatSac'), 1) }}%</h5>
                                        <p class="text-muted mb-0">Tỷ lệ xuất sắc TB</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-graduation-cap fa-2x text-info mb-2"></i>
                                        <h5>{{ $soSanhLop->sum('SoSinhVien') }}</h5>
                                        <p class="text-muted mb-0">Tổng sinh viên</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ so sánh -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-bar"></i> Biểu đồ so sánh điểm trung bình các lớp</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartSoSanhLop" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-trophy"></i> Danh sách các lớp có điểm trung bình cao nhất</h4>
                        </div>
                        <div class="card-body">
                            @foreach($soSanhLop->take(5) as $index => $lop)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <strong>{{ $lop['TenLop'] }}</strong><br>
                                    <small class="text-muted">{{ $lop['MaLop'] }}</small>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-primary">{{ number_format($lop['DiemTBLop'], 2) }}</span><br>
                                    <small class="text-muted">{{ $lop['SoSinhVien'] }} SV</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng so sánh chi tiết -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-table"></i> Bảng so sánh chi tiết</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Thứ hạng</th>
                                            <th>Mã lớp</th>
                                            <th>Tên lớp</th>
                                            <th>Số sinh viên</th>
                                            <th>Điểm TB lớp</th>
                                            <th>Tỷ lệ xuất sắc</th>
                                            <th>Đánh giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($soSanhLop as $index => $lop)
                                        <tr class="@if($index < 3) table-warning @endif">
                                            <td>
                                                @if($index < 3)
                                                    <span class="badge badge-warning badge-lg">{{ $index + 1 }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $lop['MaLop'] }}</strong></td>
                                            <td>{{ $lop['TenLop'] }}</td>
                                            <td>{{ $lop['SoSinhVien'] }}</td>
                                            <td>
                                                <span class="badge badge-primary badge-lg">{{ number_format($lop['DiemTBLop'], 2) }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ $lop['TyLeXuatSac'] }}%">
                                                        {{ number_format($lop['TyLeXuatSac'], 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($lop['DiemTBLop'] >= 8.0)
                                                    <i class="fas fa-star text-warning"></i> Xuất sắc
                                                @elseif($lop['DiemTBLop'] >= 7.0)
                                                    <i class="fas fa-graduation-cap text-info"></i> Giỏi
                                                @elseif($lop['DiemTBLop'] >= 6.0)
                                                    <i class="fas fa-thumbs-up text-primary"></i> Khá
                                                @elseif($lop['DiemTBLop'] >= 5.0)
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
        // Biểu đồ so sánh lớp
        var ctxSoSanhLop = document.getElementById('chartSoSanhLop').getContext('2d');
        new Chart(ctxSoSanhLop, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($soSanhLop as $lop)
                        "{{ $lop['TenLop'] }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Điểm TB lớp',
                    data: [
                        @foreach($soSanhLop as $lop)
                            {{ $lop['DiemTBLop'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($soSanhLop as $index => $lop)
                            @if($index < 3)
                                'rgba(255, 193, 7, 0.8)',
                            @else
                                'rgba(54, 162, 235, 0.8)',
                            @endif
                        @endforeach
                    ],
                    borderColor: [
                        @foreach($soSanhLop as $index => $lop)
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
