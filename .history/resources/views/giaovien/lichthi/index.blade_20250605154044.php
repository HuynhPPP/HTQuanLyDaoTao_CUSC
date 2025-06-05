@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách lịch phân công</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách lịch phân công</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @if ($lichCoiThi->isEmpty())
                        <div class="alert alert-info">
                            Bạn chưa được phân công coi thi.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Ngày Thi</th>
                                        <th>Môn Học</th>
                                        <th>Giờ Thi</th>
                                        <th>Phòng Thi</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lichCoiThi as $phanCong)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($phanCong->lichThi->NgayThi)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $phanCong->lichThi->monHoc->TenMH }}</td>
                                            <td>{{ $phanCong->lichThi->GioThi }}</td>
                                            <td>{{ $phanCong->lichThi->PhongThi }}</td>
                                            <td>
                                                <a href="{{ route('giaovien.lichthi.chi-tiet', ['maLichThi' => $phanCong->MaLichThi]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    Chi Tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json'
                },
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endsection
