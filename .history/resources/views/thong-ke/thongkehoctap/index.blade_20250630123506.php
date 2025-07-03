{{-- resources/views/thong-ke/bao-cao-chi-tiet.blade.php --}}
@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Báo Cáo Thống Kê Chi Tiết - {{ $chuongTrinh->TenChuongTrinh }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Tổng Quan</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Tổng Số Sinh Viên:</strong> {{ $thongKe->tong_sinh_vien }}</li>
                                    <li><strong>Điểm Trung Bình:</strong> {{ $thongKe->diem_trung_binh_tong_khoa }}</li>
                                    <li><strong>Tỷ Lệ Tốt Nghiệp:</strong> {{ $thongKe->ty_le_tot_nghiep }}%</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5>Phân Bổ Học Lực</h5>
                                <canvas id="phanBoHocLucChart"></canvas>
                            </div>
                            <div class="col-md-4">
                                <h5>Xu Hướng Điểm Số</h5>
                                <canvas id="xuHuongDiemSoChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
<script>
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
        }
    });
</script>
@endpush