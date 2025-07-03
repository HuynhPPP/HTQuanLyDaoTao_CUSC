{{-- resources/views/thong-ke/bao-cao-chi-tiet.blade.php --}}
@extends('layouts.new_app.master')

@section('page-title', 'Báo Cáo Chi Tiết Thống Kê Học Tập')

@section('custom-css')
<style>
    .statistic-card {
        margin-bottom: 20px;
    }
    .chart-container {
        height: 300px;
    }
</style>
@endsection

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Báo Cáo Chi Tiết Thống Kê Học Tập</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Thống Kê</a></div>
            <div class="breadcrumb-item">Báo Cáo Chi Tiết</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card statistic-card">
                    <div class="card-header">
                        <h4>Thống Kê Chương Trình: {{ $chuongTrinh->TenChuongTrinh ?? 'Chưa xác định' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Tổng Quan</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li><strong>Tổng Số Sinh Viên:</strong> {{ $thongKe->tong_sinh_vien ?? 0 }}</li>
                                            <li><strong>Điểm Trung Bình:</strong> {{ $thongKe->diem_trung_binh_tong_khoa ?? 0 }}</li>
                                            <li><strong>Tỷ Lệ Tốt Nghiệp:</strong> {{ $thongKe->ty_le_tot_nghiep ?? 0 }}%</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Phân Bổ Học Lực</h4>
                                    </div>
                                    <div class="card-body chart-container">
                                        <canvas id="phanBoHocLucChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Xu Hướng Điểm Số</h4>
                                    </div>
                                    <div class="card-body chart-container">
                                        <canvas id="xuHuongDiemSoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        // Biểu đồ phân bổ học lực
        var phanBoHocLucCtx = document.getElementById('phanBoHocLucChart').getContext('2d');
        var phanBoHocLucChart = new Chart(phanBoHocLucCtx, {
            type: 'pie',
            data: {
                labels: @json($bieuDoPhanBoHocLuc['labels']),
                datasets: [{
                    data: @json($bieuDoPhanBoHocLuc['data']),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Biểu đồ xu hướng điểm số
        var xuHuongDiemSoCtx = document.getElementById('xuHuongDiemSoChart').getContext('2d');
        var xuHuongDiemSoChart = new Chart(xuHuongDiemSoCtx, {
            type: 'line',
            data: {
                labels: @json($xuHuongDiemSo->pluck('hoc_ki')),
                datasets: [{
                    label: 'Điểm Trung Bình',
                    data: @json($xuHuongDiemSo->pluck('diem_trung_binh')),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection
