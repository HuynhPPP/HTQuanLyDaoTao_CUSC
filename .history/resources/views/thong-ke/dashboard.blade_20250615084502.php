@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Bảng điều khiển thống kê</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="#">Trang chủ</a></div>
                <div class="breadcrumb-item">Thống kê</div>
            </div>
        </div>

        <div class="section-body">
            {{-- Cards thống kê --}}
            <div class="row">
                @php
                    $cardClass = 'col-lg-4 col-md-6 col-12 mb-4';
                @endphp

                {{-- Sinh viên --}}
                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng Sinh Viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongSinhVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNam }} | Nữ: {{ $tongNu }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Giáo viên --}}
                <div class="{{ $cardClass }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tổng Giảng Viên</h4>
                            </div>
                            <div class="card-body">
                                {{ $tongGiaoVien }}
                                <small class="text-muted d-block">Nam: {{ $tongNamGV }} | Nữ: {{ $tongNuGV }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ --}}
            <div class="row">

                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biểu Đồ Giới Tính Sinh Viên</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartGioiTinh" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hocLucChart = document.getElementById('chartHocLuc').getContext('2d');
            const gioiTinhChart = document.getElementById('chartGioiTinh').getContext('2d');

            // Biểu đồ học lực
            new Chart(hocLucChart, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($hocLucPhanLoai->keys()) !!},
                    datasets: [{
                        label: 'Số lượng',
                        data: {!! json_encode($hocLucPhanLoai->values()) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: 'white',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });

            // Biểu đồ giới tính
            new Chart(gioiTinhChart, {
                type: 'doughnut',
                data: {
                    labels: ['Nam', 'Nữ'],
                    datasets: [{
                        data: [{{ $tongNam }}, {{ $tongNu }}],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: 'white',
                        borderWidth: 1
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
        });
    </script>
@endsection
