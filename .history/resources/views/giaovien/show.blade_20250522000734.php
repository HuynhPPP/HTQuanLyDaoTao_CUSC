@extends('layouts.new_app.master')

@section('main-content')
    <div class="section my-5">
        <div class="section-header">
            <h1>Quản lý giáo viên</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('giaovien.index') }}">Danh sách giáo viên</a></div>
                    <div class="breadcrumb-item">Thêm sinh viên mới</div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Thông tin chi tiết giáo viên</h5>
                            <div>
                                <a href="{{ route('giaovien.edit', $giaovien->MaGV) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="{{ route('giaovien.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin cá nhân</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Mã giáo viên</th>
                                            <td>{{ $giaovien->MaGV }}</td>
                                        </tr>
                                        <tr>
                                            <th>Giới tính</th>
                                            <td>{{ $giaovien->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Loại giáo viên</th>
                                            <td>
                                                {{ $giaovien->LoaiGV == 'CoHuu' ? 'Cơ hữu' : 'Mời giảng' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Học vị</th>
                                            <td>{{ optional($giaovien->hocvi)->TenHocVi ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Chức vụ</th>
                                            <td>{{ optional($giaovien->chucvu)->TenChucVu ?? 'Chưa có' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin liên hệ</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">Email</th>
                                            <td>{{ $giaovien->Email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại</th>
                                            <td>{{ $giaovien->Sdt ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Đơn vị</th>
                                            <td>{{ optional($giaovien->donvi)->TenDVHienTai ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Chuyên ngành</th>
                                            <td>{{ $giaovien->ChuyenNganh ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Bằng cấp và thông tin chuyên môn</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">Bằng cấp</th>
                                            <td>{{ optional($giaovien->bangcapcanbo)->TenBang ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian cấp</th>
                                            <td>
                                                {{ optional($giaovien->bangcapcanbo)->ThoiGianCap 
                                                    ? \Carbon\Carbon::parse($giaovien->bangcapcanbo->ThoiGianCap)->format('d/m/Y') 
                                                    : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Đơn vị cấp</th>
                                            <td>{{ optional($giaovien->bangcapcanbo)->DonViCap ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Thời gian công tác</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">Ngày bắt đầu</th>
                                            <td>
                                                {{ $giaovien->NgayBatDauCongTac 
                                                    ? \Carbon\Carbon::parse($giaovien->NgayBatDauCongTac)->format('d/m/Y') 
                                                    : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ghi chú</th>
                                            <td>{{ $giaovien->GhiChu ?? 'Không có ghi chú' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
