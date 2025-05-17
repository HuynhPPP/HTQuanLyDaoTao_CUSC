@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm tập huấn mới</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('taphuan.index') }}">Danh sách tập huấn</a></div>
                <div class="breadcrumb-item">Thêm tập huấn mới</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('taphuan.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mã Tập Huấn <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="MaTapHuan" class="form-control @error('MaTapHuan') is-invalid @enderror" 
                                               value="{{ old('MaTapHuan') }}" required>
                                        @error('MaTapHuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Khóa Tập Huấn</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenKhoaTapHuan" class="form-control" 
                                               value="{{ old('TenKhoaTapHuan') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Bắt Đầu</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="ThoiGianBatDau" class="form-control" 
                                               value="{{ old('ThoiGianBatDau') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Kết Thúc</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="ThoiGianKetThuc" class="form-control" 
                                               value="{{ old('ThoiGianKetThuc') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Địa Điểm</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="DiaDiem" class="form-control" 
                                               value="{{ old('DiaDiem') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                        <a href="{{ route('taphuan.index') }}" class="btn btn-secondary">Hủy</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection