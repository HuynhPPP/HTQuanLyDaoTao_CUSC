@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Quản lý cán bộ</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('staff.index') }}">Danh sách cán bộ</a></div>
                <div class="breadcrumb-item">Chỉnh sửaThông tin cán bộ</div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa thông tin cán bộ</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staff.update', $canbo->MaCB) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaCB" class="form-label">Mã cán bộ</label>
                                    <input type="text" class="form-control" id="MaCB" name="MaCB"
                                        value="{{ $canbo->MaCB }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="HoTenCB" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control @error('HoTenCB') is-invalid @enderror"
                                        id="HoTenCB" name="HoTenCB" value="{{ old('HoTenCB', $canbo->HoTenCB) }}">
                                    @error('HoTenCB')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="GioiTinh" class="form-label">Giới tính</label>
                                    <select class="form-control @error('GioiTinh') is-invalid @enderror" id="GioiTinh"
                                        name="GioiTinh">
                                        <option value="">Chọn giới tính</option>
                                        <option value="1"
                                            {{ old('GioiTinh', $canbo->GioiTinh) == '1' ? 'selected' : '' }}>Nam</option>
                                        <option value="0"
                                            {{ old('GioiTinh', $canbo->GioiTinh) == '0' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('GioiTinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                        id="Email" name="Email" value="{{ old('Email', $canbo->Email) }}">
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Sdt" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                        id="Sdt" name="Sdt" value="{{ old('Sdt', $canbo->Sdt) }}">
                                    @error('Sdt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="MaHV" class="form-label">Học vị</label>
                                    <select class="form-control @error('MaHV') is-invalid @enderror" id="MaHV"
                                        name="MaHV">
                                        <option value="">Chọn học vị</option>
                                        @foreach ($hocvis as $hv)
                                            <option value="{{ $hv->MaHV }}" {{ old('MaHV', $canbo->MaHV) == $hv->MaHV ? 'selected' : '' }}>
                                                {{ $hv->TenHocVi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaHV')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="TenChucVu" class="form-label">Chức vụ</label>
                                    <select class="form-control @error('TenChucVu') is-invalid @enderror" id="TenChucVu"
                                        name="TenChucVu">
                                        <option value="">Chọn chức vụ</option>
                                        @foreach ($chucvus as $cv)
                                            <option value="{{ $cv->TenChucVu }}" {{ old('TenChucVu', $canbo->TenChucVu) == $cv->TenChucVu ? 'selected' : '' }}>
                                                {{ $cv->TenChucVu }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('TenChucVu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="CongViecPhuTrach" class="form-label">Công việc phụ trách</label>
                                    <input type="text" class="form-control @error('CongViecPhuTrach') is-invalid @enderror"
                                        id="CongViecPhuTrach" name="CongViecPhuTrach" value="{{ old('CongViecPhuTrach', $canbo->CongViecPhuTrach) }}">
                                    @error('CongViecPhuTrach')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaDV" class="form-label">Đơn vị</label>
                                    <select class="form-control @error('MaDV') is-invalid @enderror" id="MaDV"
                                        name="MaDV">
                                        <option value="">Chọn đơn vị</option>
                                        @foreach ($donvis as $dv)
                                            <option value="{{ $dv->MaDV }}" {{ old('MaDV', $canbo->MaDV) == $dv->MaDV ? 'selected' : '' }}>
                                                {{ $dv->TenDVHienTai }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaDV')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="MaBang" class="form-label">Bằng cấp</label>
                                    <select class="form-control @error('MaBang') is-invalid @enderror" id="MaBang"
                                        name="MaBang">
                                        <option value="">Chọn bằng cấp</option>
                                        @foreach ($bangcaps as $bc)
                                            <option value="{{ $bc->MaBang }}" {{ old('MaBang', $canbo->MaBang) == $bc->MaBang ? 'selected' : '' }}>
                                                {{ $bc->TenBang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaBang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaTapHuan" class="form-label">Khóa tập huấn</label>
                                    <select class="form-control @error('MaTapHuan') is-invalid @enderror" id="MaTapHuan"
                                        name="MaTapHuan">
                                        <option value="">Chọn khóa tập huấn</option>
                                        @foreach ($taphuans as $th)
                                            <option value="{{ $th->MaTapHuan }}" {{ old('MaTapHuan', $canbo->MaTapHuan) == $th->MaTapHuan ? 'selected' : '' }}>
                                                {{ $th->TenKhoaTapHuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaTapHuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ThoiGianBDCongTacCUSC" class="form-label">Thời gian bắt đầu công tác</label>
                                    <input type="date" class="form-control @error('ThoiGianBDCongTacCUSC') is-invalid @enderror"
                                        id="ThoiGianBDCongTacCUSC" name="ThoiGianBDCongTacCUSC" value="{{ old('ThoiGianBDCongTacCUSC', $canbo->ThoiGianBDCongTacCUSC) }}">
                                    @error('ThoiGianBDCongTacCUSC')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ThoiGianKTCongTacCUSC" class="form-label">Thời gian kết thúc công tác</label>
                                    <input type="date" class="form-control @error('ThoiGianKTCongTacCUSC') is-invalid @enderror"
                                        id="ThoiGianKTCongTacCUSC" name="ThoiGianKTCongTacCUSC" value="{{ old('ThoiGianKTCongTacCUSC', $canbo->ThoiGianKTCongTacCUSC) }}">
                                    @error('ThoiGianKTCongTacCUSC')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection