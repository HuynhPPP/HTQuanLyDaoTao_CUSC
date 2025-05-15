@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Chỉnh sửa bằng cấp cán bộ</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bangcapcanbo.update', $bangcap->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="MaBang" class="form-label">Mã bằng</label>
                                    <input type="text" class="form-control @error('MaBang') is-invalid @enderror"
                                        id="MaBang" name="MaBang" value="{{ old('MaBang', $bangcap->MaBang) }}">
                                    @error('MaBang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="TenBang" class="form-label">Tên bằng</label>
                                    <input type="text" class="form-control @error('TenBang') is-invalid @enderror"
                                        id="TenBang" name="TenBang" value="{{ old('TenBang', $bangcap->TenBang) }}">
                                    @error('TenBang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ThoiGianCap" class="form-label">Thời gian cấp</label>
                                    <input type="date"
                                        class="form-control @error('ThoiGianCap') is-invalid @enderror" id="ThoiGianCap"
                                        name="ThoiGianCap" value="{{ old('ThoiGianCap', $bangcap->ThoiGianCap) }}">
                                    @error('ThoiGianCap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="DonViCap" class="form-label">Đơn vị cấp</label>
                                    <input type="text" class="form-control @error('DonViCap') is-invalid @enderror"
                                        id="DonViCap" name="DonViCap" value="{{ old('DonViCap', $bangcap->DonViCap) }}">
                                    @error('DonViCap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="SoHieu" class="form-label">Số hiệu</label>
                                    <input type="text" class="form-control @error('SoHieu') is-invalid @enderror"
                                        id="SoHieu" name="SoHieu" value="{{ old('SoHieu', $bangcap->SoHieu) }}">
                                    @error('SoHieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="SoVaoSo" class="form-label">Số vào sổ</label>
                                    <input type="text" class="form-control @error('SoVaoSo') is-invalid @enderror"
                                        id="SoVaoSo" name="SoVaoSo" value="{{ old('SoVaoSo', $bangcap->SoVaoSo) }}">
                                    @error('SoVaoSo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('bangcapcanbo.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
