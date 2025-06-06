@extends('layouts.new_app.app')

@section('content')
<div class="section-header">
    <h1>Chi Tiết Lịch Thi</h1>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Thông Tin Chi Tiết</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Thông Tin Kỳ Thi</h5>
                    <table class="table">
                        <tr>
                            <th>Môn Học</th>
                            <td>{{ $lichThi->monHoc->TenMH }}</td>
                        </tr>
                        <tr>
                            <th>Ngày Thi</th>
                            <td>{{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Giờ Thi</th>
                            <td>{{ $lichThi->GioThi }}</td>
                        </tr>
                        <tr>
                            <th>Phòng Thi</th>
                            <td>{{ $lichThi->PhongThi }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Thông Tin Phân Công</h5>
                    <table class="table">
                        <tr>
                            <th>Vai Trò</th>
                            <td>{{ $phanCongThi->VaiTro }}</td>
                        </tr>
                        <tr>
                            <th>Ghi Chú</th>
                            <td>{{ $phanCongThi->GhiChu ?? 'Không có' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('giaovien.lichthi.index') }}" class="btn btn-secondary">Quay Lại</a>
        </div>
    </div>
</div>
@endsection 