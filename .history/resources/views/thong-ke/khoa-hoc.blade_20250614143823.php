@extends('thong-ke.layout')

@section('thong-ke-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thống Kê Khóa Học</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="section-title">Bảng Thống Kê Chi Tiết</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mã Khóa</th>
                                            <th>Tên Khóa Học</th>
                                            <th>Loại Khóa</th>
                                            <th>Tổng SV</th>
                                            <th>Điểm TB</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tongSinhVien = 0;
                                            $tongDiemTB = 0;
                                        @endphp
                                        @foreach ($khoaHoc as $khoa)
                                            <tr>
                                                <td>{{ $khoa->MaChuongTrinh }}</td>
                                                <td>{{ $khoa->TenChuongTrinh }}</td>
                                                <td>{{ $khoa->TenKhoaDaoTao }}</td>
                                                <td>{{ $khoa->tong_sinh_vien }}</td>
                                                <td>{{ number_format($khoa->diem_trung_binh, 2) }}</td>
                                            </tr>
                                            @php
                                                $tongSinhVien += $khoa->tong_sinh_vien;
                                                $tongDiemTB += $khoa->diem_trung_binh;
                                            @endphp
                                        @endforeach
                                        <tr class="table-primary">
                                            <td colspan="3"><strong>Tổng</strong></td>
                                            <td><strong>{{ $tongSinhVien }}</strong></td>
                                            <td><strong>{{ number_format($tongDiemTB / count($khoaHoc), 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="section-title">Biểu Đồ Khóa Học</h6>
                            <canvas id="chartKhoaHoc" height="300"></canvas>
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
            const ctx = document.getElementById('chartKhoaHoc').getContext('2d');

            const labels = {!! json_encode($khoaHoc->pluck('MaChuongTrinh')) !!};
            const tongSinhVien = {!! json_encode($khoaHoc->pluck('tong_sinh_vien')) !!};
            const diemTrungBinh = {!! json_encode($khoaHoc->pluck('diem_trung_binh')) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Tổng Sinh Viên',
                            data: tongSinhVien,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Điểm Trung Bình',
                            data: diemTrungBinh,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
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
                            text: 'Thống Kê Khóa Học'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Mã Khóa Học'
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Tổng Sinh Viên'
                            },
                            beginAtZero: true
                        },
                        y2: {
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Điểm Trung Bình'
                            },
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });
        });
    </script>
@endpush
