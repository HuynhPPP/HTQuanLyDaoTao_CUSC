@extends('layouts.new_app.master')

@section('title', 'Chỉnh Sửa Học Kỳ')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh sửa học kỳ</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('hocki.index') }}">Danh sách học kỳ</a>
                </div>
                <div class="breadcrumb-item">Chỉnh sửa</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Cập nhật thông tin học kỳ</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hocki.update', $hocki->MaHK) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã học kỳ <span class="text-danger">*</span></label>
                                    <input type="text" name="MaHK" class="form-control"
                                        value="{{ $hocki->MaHK }}">
                                    @error('MaHK')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên học kỳ <span class="text-danger">*</span></label>
                                    <input type="text" name="TenHK" class="form-control"
                                        value="{{ old('TenHK', $hocki->TenHK) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tổng giờ gốc</label>
                                    <input type="number" name="TongGioGoc" class="form-control"
                                        value="{{ old('TongGioGoc', $hocki->TongGioGoc) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tổng giờ triển khai</label>
                                    <input type="number" name="TongGioTrienKhai" class="form-control"
                                        value="{{ old('TongGioTrienKhai', $hocki->TongGioTrienKhai) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="MaChuongTrinh" class="form-label">Chương trình <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('MaChuongTrinh') is-invalid @enderror"
                                        id="MaChuongTrinh" name="MaChuongTrinh">
                                        <option value="">-- Chọn chương trình --</option>
                                        @foreach ($chuongtrinhs as $chuongtrinh)
                                            <option value="{{ $chuongtrinh->MaChuongTrinh }}"
                                                {{ old('MaChuongTrinh', $hocki->MaChuongTrinh) == $chuongtrinh->MaChuongTrinh ? 'selected' : '' }}>
                                                {{ $chuongtrinh->TenChuongTrinh }}</option>
                                        @endforeach
                                    </select>
                                    @error('MaChuongTrinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('hocki.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection