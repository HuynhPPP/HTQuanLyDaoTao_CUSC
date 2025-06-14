@extends('thong-ke.layout')

@section('thong-ke-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Phân Loại Học Lực Sinh Viên</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h6 class="section-title">Bảng Phân Loại Học Lực</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mã Sinh Viên</th>
                                            <th>Điểm TB</th>
                                            <th>Học Lực</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $hocLucThongKe = [
                                                'Xuất sắc' => 0,
                                                'Giỏi' => 0,
                                                'Khá' => 0,
                                                'Trung bình' => 0,
                                                'Yếu' => 0,
                                            ];
                                        @endphp
                                        @foreach ($hocLuc as $sv)
                                            <tr>
                                                <td>{{ $sv->MaSV }}</td>
                                                <td>{{ number_format($sv->diem_trung_binh, 2) }}</td>
                                                <td>
                                                    @php
                                                        $hocLucThongKe[$sv->hoc_luc]++;
                                                    @endphp
                                                    <span
                                                        class="badge 
                                                @switch($sv->hoc_luc)
                                                    @case('Xuất sắc')
                                                        badge-success
                                                        @break
                                                    @case('Giỏi')
                                                        badge-primary
                                                        @break
                                                    @case('Khá')
                                                        badge-info
                                                        @break
                                                    @case('Trung bình')
                                                        badge-warning
                                                        @break
                                                    @default
                                                        badge-danger
                                                @endswitch
                                            ">
                                                        {{ $sv->hoc_luc }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h6 class="section-title">Biểu Đồ Phân Bổ Học Lực</h6>
                            <canvas id="chartHocLuc" height="300"></canvas>
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
            const ctx = document.getElementById('chartHocLuc').getContext('2d');

            const hocLucThongKe = {!! json_encode(array_values($hocLucThongKe)) !!};
            const labels = {!! json_encode(array_keys($hocLucThongKe)) !!};

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: hocLucThongKe,
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.6)', // Xuất sắc
                            'rgba(0, 123, 255, 0.6)', // Giỏi
                            'rgba(23, 162, 184, 0.6)', // Khá
                            'rgba(255, 193, 7, 0.6)', // Trung bình
                            'rgba(220, 53, 69, 0.6)' // Yếu
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Phân Bổ Học Lực Sinh Viên'
                        }
                    }
                }
            });
        });
    </script>
@endpush
