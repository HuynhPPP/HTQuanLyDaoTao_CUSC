@extends('thong-ke.layout')

@section('thong-ke-content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Thống Kê Sinh Viên Theo Lớp</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="section-title">Bảng Thống Kê Chi Tiết</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã Lớp</th>
                                        <th>Tổng Số</th>
                                        <th>Nam</th>
                                        <th>Nữ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tongSinhVien = 0;
                                        $tongNam = 0;
                                        $tongNu = 0;
                                    @endphp
                                    @foreach($thongKe as $lop)
                                    <tr>
                                        <td>{{ $lop->MaLop }}</td>
                                        <td>{{ $lop->tong_so_luong }}</td>
                                        <td>{{ $lop->nam }}</td>
                                        <td>{{ $lop->nu }}</td>
                                    </tr>
                                    @php
                                        $tongSinhVien += $lop->tong_so_luong;
                                        $tongNam += $lop->nam;
                                        $tongNu += $lop->nu;
                                    @endphp
                                    @endforeach
                                    <tr class="table-primary">
                                        <td><strong>Tổng</strong></td>
                                        <td><strong>{{ $tongSinhVien }}</strong></td>
                                        <td><strong>{{ $tongNam }}</strong></td>
                                        <td><strong>{{ $tongNu }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Biểu Đồ Phân Bổ Sinh Viên</h6>
                        <canvas id="chartSinhVien" height="300"></canvas>
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
    const ctx = document.getElementById('chartSinhVien').getContext('2d');
    
    const labels = {!! json_encode($thongKe->pluck('MaLop')) !!};
    const nam = {!! json_encode($thongKe->pluck('nam')) !!};
    const nu = {!! json_encode($thongKe->pluck('nu')) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nam',
                    data: nam,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Nữ',
                    data: nu,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Phân Bổ Sinh Viên Theo Lớp'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mã Lớp'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Số Lượng Sinh Viên'
                    },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush 