@extends('layouts.new_app.master')

@section('main-content')
    <div class="section my-5">
        <div class="section-header">
            <h1>Quản lý cán bộ</h1>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Thông tin chi tiết cán bộ</h5>
                            <div>
                                <a href="{{ route('staff.edit', $canbo->MaCB) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
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
                                            <th>Học vị</th>
                                            <td>{{ optional($canbo->hocvi)->TenHocVi ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Chức vụ</th>
                                            <td>{{ optional($canbo->chucvu)->TenChucVu ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Đơn vị</th>
                                            <td>{{ optional($canbo->donvi)->TenDVHienTai ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bằng cấp</th>
                                            <td>{{ optional($canbo->bangcapcanbo)->TenBang ?? 'Chưa có' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tập huấn</th>
                                            <td>{{ optional($canbo->taphuan)->TenKhoaTapHuan ?? 'Chưa có' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin liên hệ</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">Email</th>
                                            <td>{{ $canbo->Email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại</th>
                                            <td>{{ $canbo->Sdt }}</td>
                                        </tr>
                                        <tr>
                                            <th>Đơn vị</th>
                                            <td>{{ $canbo->donvi->TenDVHienTai ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Công việc phụ trách</th>
                                            <td>{{ $canbo->CongViecPhuTrach ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Bằng cấp và tập huấn</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">Bằng cấp</th>
                                            <td>{{ $canbo->bangcapcanbo->TenBang ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian cấp</th>
                                            <td>{{ $canbo->bangcapcanbo->ThoiGianCap ? \Carbon\Carbon::parse($canbo->bangcapcanbo->ThoiGianCap)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Đơn vị cấp</th>
                                            <td>{{ $canbo->bangcapcanbo->DonViCap ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Khóa tập huấn</th>
                                            <td>{{ $canbo->taphuan->TenKhoaTapHuan ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian tập huấn</th>
                                            <td>
                                                @if ($canbo->taphuan && $canbo->taphuan->ThoiGianBatDau && $canbo->taphuan->ThoiGianKetThuc)
                                                    {{ \Carbon\Carbon::parse($canbo->taphuan->ThoiGianBatDau)->format('d/m/Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($canbo->taphuan->ThoiGianKetThuc)->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Thời gian công tác</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%">Thời gian bắt đầu</th>
                                            <td>{{ $canbo->ThoiGianBDCongTacCUSC ? \Carbon\Carbon::parse($canbo->ThoiGianBDCongTacCUSC)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian kết thúc</th>
                                            <td>{{ $canbo->ThoiGianKTCongTacCUSC ? \Carbon\Carbon::parse($canbo->ThoiGianKTCongTacCUSC)->format('d/m/Y') : 'N/A' }}
                                            </td>
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
