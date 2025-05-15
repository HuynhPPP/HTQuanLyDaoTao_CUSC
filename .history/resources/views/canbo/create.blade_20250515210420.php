@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thêm cán bộ mới</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <!-- Form nhập cán bộ thủ công -->
                            <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="MaCB" class="form-label">Mã cán bộ</label>
                                        <input type="text" class="form-control @error('MaCB') is-invalid @enderror"
                                            id="MaCB" name="MaCB" value="{{ old('MaCB') }}">
                                        @error('MaCB')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="HoTenCB" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control @error('HoTenCB') is-invalid @enderror"
                                            id="HoTenCB" name="HoTenCB" value="{{ old('HoTenCB') }}">
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
                                            <option value="1" {{ old('GioiTinh') == '1' ? 'selected' : '' }}>Nam</option>
                                            <option value="0" {{ old('GioiTinh') == '0' ? 'selected' : '' }}>Nữ</option>
                                        </select>
                                        @error('GioiTinh')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                            id="Email" name="Email" value="{{ old('Email') }}">
                                        @error('Email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="Sdt" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                            id="Sdt" name="Sdt" value="{{ old('Sdt') }}">
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
                                                <option value="{{ $hv->MaHV }}" {{ old('MaHV') == $hv->MaHV ? 'selected' : '' }}>
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
                                                <option value="{{ $cv->TenChucVu }}" {{ old('TenChucVu') == $cv->TenChucVu ? 'selected' : '' }}>
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
                                            id="CongViecPhuTrach" name="CongViecPhuTrach" value="{{ old('CongViecPhuTrach') }}">
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
                                                <option value="{{ $dv->MaDV }}" {{ old('MaDV') == $dv->MaDV ? 'selected' : '' }}>
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
                                                <option value="{{ $bc->MaBang }}" {{ old('MaBang') == $bc->MaBang ? 'selected' : '' }}>
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
                                                <option value="{{ $th->MaTapHuan }}" {{ old('MaTapHuan') == $th->MaTapHuan ? 'selected' : '' }}>
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
                                            id="ThoiGianBDCongTacCUSC" name="ThoiGianBDCongTacCUSC" value="{{ old('ThoiGianBDCongTacCUSC') }}">
                                        @error('ThoiGianBDCongTacCUSC')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="ThoiGianKTCongTacCUSC" class="form-label">Thời gian kết thúc công tác</label>
                                        <input type="date" class="form-control @error('ThoiGianKTCongTacCUSC') is-invalid @enderror"
                                            id="ThoiGianKTCongTacCUSC" name="ThoiGianKTCongTacCUSC" value="{{ old('ThoiGianKTCongTacCUSC') }}">
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
                                        <i class="fas fa-save"></i> Lưu thông tin
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 