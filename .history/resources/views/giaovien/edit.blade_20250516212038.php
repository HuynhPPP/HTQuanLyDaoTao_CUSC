@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Quản lý giáo viên</h1>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa thông tin giáo viên</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('giaovien.update', $giaovien->MaGV) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaGV" class="form-label">Mã giáo viên</label>
                                    <input type="text" class="form-control" id="MaGV" name="MaGV"
                                        value="{{ $giaovien->MaGV }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="HoTenGV" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control @error('HoTenGV') is-invalid @enderror"
                                        id="HoTenGV" name="HoTenGV" value="{{ old('HoTenGV', $giaovien->HoTenGV) }}">
                                    @error('HoTenGV')
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
                                            {{ old('GioiTinh', $giaovien->GioiTinh) == '1' ? 'selected' : '' }}>Nam</option>
                                        <option value="0"
                                            {{ old('GioiTinh', $giaovien->GioiTinh) == '0' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('GioiTinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('Email') is-invalid @enderror"
                                        id="Email" name="Email" value="{{ old('Email', $giaovien->Email) }}">
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Sdt" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('Sdt') is-invalid @enderror"
                                        id="Sdt" name="Sdt" value="{{ old('Sdt', $giaovien->Sdt) }}">
                                    @error('Sdt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="LoaiGV" class="form-label">Loại giáo viên</label>
                                    <select class="form-control @error('LoaiGV') is-invalid @enderror" id="LoaiGV"
                                        name="LoaiGV">
                                        <option value="">Chọn loại giáo viên</option>
                                        <option value="CoHuu"
                                            {{ old('LoaiGV', $giaovien->LoaiGV) == 'CoHuu' ? 'selected' : '' }}>Cơ hữu
                                        </option>
                                        <option value="MoiGiang"
                                            {{ old('LoaiGV', $giaovien->LoaiGV) == 'MoiGiang' ? 'selected' : '' }}>Mời giảng
                                        </option>
                                    </select>
                                    @error('LoaiGV')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaHV" class="form-label">Học vị</label>
                                    <select class="form-control @error('MaHV') is-invalid @enderror" id="MaHV"
                                        name="MaHV">
                                        <option value="">Chọn học vị</option>
                                        @foreach ($hocvis as $hv)
                                            <option value="{{ $hv->MaHV }}"
                                                {{ old('MaHV', $giaovien->MaHV) == $hv->MaHV ? 'selected' : '' }}>
                                                {{ $hv->TenHocVi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaHV')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="TenChucVu" class="form-label">Chức vụ</label>
                                    <select class="form-control @error('TenChucVu') is-invalid @enderror" id="TenChucVu"
                                        name="TenChucVu">
                                        <option value="">Chọn chức vụ</option>
                                        @foreach ($chucvus as $cv)
                                            <option value="{{ $cv->TenChucVu }}"
                                                {{ old('TenChucVu', $giaovien->TenChucVu) == $cv->TenChucVu ? 'selected' : '' }}>
                                                {{ $cv->TenChucVu }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('TenChucVu')
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
                                            <option value="{{ $dv->MaDV }}"
                                                {{ old('MaDV', $giaovien->MaDV) == $dv->MaDV ? 'selected' : '' }}>
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
                                            <option value="{{ $bc->MaBang }}"
                                                {{ old('MaBang', $giaovien->MaBang) == $bc->MaBang ? 'selected' : '' }}>
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
                                    <label for="ChuyenNganh" class="form-label">Chuyên ngành</label>
                                    <input type="text"
                                        class="form-control @error('ChuyenNganh') is-invalid @enderror"
                                        id="ChuyenNganh" name="ChuyenNganh"
                                        value="{{ old('ChuyenNganh', $giaovien->ChuyenNganh) }}">
                                    @error('ChuyenNganh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="NgayBatDauCongTac" class="form-label">Ngày bắt đầu công tác</label>
                                    <input type="date"
                                        class="form-control @error('NgayBatDauCongTac') is-invalid @enderror"
                                        id="NgayBatDauCongTac" name="NgayBatDauCongTac"
                                        value="{{ old('NgayBatDauCongTac', $giaovien->NgayBatDauCongTac) }}">
                                    @error('NgayBatDauCongTac')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="GhiChu" class="form-label">Ghi chú</label>
                                    <textarea class="form-control @error('GhiChu') is-invalid @enderror" id="GhiChu"
                                        name="GhiChu" rows="3">{{ old('GhiChu', $giaovien->GhiChu) }}</textarea>
                                    @error('GhiChu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('giaovien.index') }}" class="btn btn-secondary">
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