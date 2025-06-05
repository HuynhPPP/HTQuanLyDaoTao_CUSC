@extends('layouts.new_app.master')

@section('title', 'Thêm Mới Môn Học')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm mới môn học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('monhoc.index') }}">Danh sách môn học</a></div>
                <div class="breadcrumb-item">Thêm mới môn học</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Nhập thông tin môn học</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('monhoc.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã môn học <span class="text-danger">*</span></label>
                                    <input type="text" name="MaMH"
                                        class="form-control @error('MaMH') is-invalid @enderror"
                                        value="{{ old('MaMH') }}">
                                    @error('MaMH')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên môn học <span class="text-danger">*</span></label>
                                    <input type="text" name="TenMH"
                                        class="form-control @error('TenMH') is-invalid @enderror"
                                        value="{{ old('TenMH') }}">
                                    @error('TenMH')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giờ gốc</label>
                                    <input type="number" name="GioGoc" class="form-control"
                                        value="{{ old('GioGoc') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giờ triển khai</label>
                                    <input type="number" name="GioTrienKhai" class="form-control"
                                        value="{{ old('GioTrienKhai') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietLT" name="TietLT" value="1"
                                            {{ old('TietLT') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietLT">Tiết lý thuyết</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietTH" name="TietTH" value="1"
                                            {{ old('TietTH') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietTH">Tiết thực hành</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="TietLTvaTH" name="TietLTvaTH" value="1"
                                            {{ old('TietLTvaTH') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="TietLTvaTH">Tiết LT và TH</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('monhoc.index') }}" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu Môn Học</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection