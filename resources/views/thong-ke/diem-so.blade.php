@extends('thong-ke.layout')

@section('thong-ke-content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Thống Kê Điểm Số</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="section-title">Bảng Thống Kê Chi Tiết</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã MH</th>
                                        <th>Điểm TB</th>
                                        <th>Tổng SV</th>
                                        <th>SV Đạt</th>
                                        <th>Tỷ Lệ Đạt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tongSinhVien = 0;
                                        $tongSinhVienDat = 0;
                                        $tongDiemTB = 0;
                                    @endphp
                                    @foreach($thongKeDiem as $monHoc)
                                    <tr>
                                        <td>{{ $monHoc->MaMH }}</td>
                                        <td>{{ number_format($monHoc->diem_trung_binh, 2) }}</td>
                                        <td>{{ $monHoc->tong_so_luong }}</td>
                                        <td>{{ $monHoc->so_luong_dat }}</td>
                                        <td>{{ number_format($monHoc->ty_le_dat, 2) }}%</td>
                                    </tr>
                                    @php
                                        $tongSinhVien += $monHoc->tong_so_luong;
                                        $tongSinhVienDat += $monHoc->so_luong_dat;
                                        $tongDiemTB += $monHoc->diem_trung_binh;
                                    @endphp
                                    @endforeach
                                    <tr class="table-primary">
                                        <td><strong>Tổng</strong></td>
                                        <td><strong>{{ number_format($tongDiemTB / count($thongKeDiem), 2) }}</strong></td>
                                        <td><strong>{{ $tongSinhVien }}</strong></td>
                                        <td><strong>{{ $tongSinhVienDat }}</strong></td>
                                        <td><strong>{{ number_format(($tongSinhVienDat / $tongSinhVien) * 100, 2) }}%</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Biểu Đồ Điểm Số</h6>
                        <canvas id="chartDiemSo" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartDiemSo').getContext('2d');
    
    const labels = {!! json_encode($thongKeDiem->pluck('MaMH')) !!};
    const diemTrungBinh = {!! json_encode($thongKeDiem->pluck('diem_trung_binh')) !!};
    const tyLeDat = {!! json_encode($thongKeDiem->pluck('ty_le_dat')) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Điểm Trung Bình',
                    data: diemTrungBinh,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1'
                },
                {
                    label: 'Tỷ Lệ Đạt (%)',
                    data: tyLeDat,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Thống Kê Điểm Số Theo Môn Học'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mã Môn Học'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Điểm Trung Bình'
                    },
                    beginAtZero: true,
                    max: 10
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Tỷ Lệ Đạt (%)'
                    },
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush 