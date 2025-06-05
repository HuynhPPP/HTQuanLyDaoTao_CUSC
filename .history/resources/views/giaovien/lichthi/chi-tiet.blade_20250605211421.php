@extends('layouts.new_app.master')

@section('main-content')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thông tin chi tiết lịch thi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('giaovien.lichthi.index') }}">Danh sách lịch phân
                        công</a></div>
                <div class="breadcrumb-item">Thông tin chi tiết lịch thi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin kỳ thi</h5>
                            <table class="table">
                                <tr>
                                    <th>Môn học</th>
                                    <td>{{ $lichThi->monHoc->TenMH }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày thi</th>
                                    <td>{{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Giờ thi</th>
                                    <td>{{ $lichThi->KhungGio }}</td>
                                </tr>
                                <tr>
                                    <th>Phòng thi</th>
                                    <td>{{ $lichThi->PhongThi }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin phân công</h5>
                            <table class="table">
                                <tr>
                                    <th>Vai trò</th>
                                    <td>{{ $phanCongThi->VaiTro }}</td>
                                </tr>
                                <tr>
                                    <th>Ghi chú</th>
                                    <td>{{ $phanCongThi->GhiChu ?? 'Không có' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-hover" id="table-1">
                            <thead class="thead-light">
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ Tên</th>
                                    <th>Email</th>
                                    <th>Số ĐT</th>
                                    <th>Ghi Chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($danhSachSinhVien as $sinhVien)
                                    <tr>
                                        <td>{{ $sinhVien->MaSV }}</td>
                                        <td>{{ $sinhVien->HoTen }}</td>
                                        <td>{{ $sinhVien->Email }}</td>
                                        <td>{{ $sinhVien->Sdt }}</td>
                                        <td>
                                            <input type="text" name="sinhvien[{{ $sinhVien->MaSV }}][GhiChu]"
                                                class="form-control form-control-sm ghi-chu-input"
                                                value="{{ $sinhVien->GhiChu }}" placeholder="Ghi chú (nếu có)">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('giaovien.lichthi.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </section>
@endsection
