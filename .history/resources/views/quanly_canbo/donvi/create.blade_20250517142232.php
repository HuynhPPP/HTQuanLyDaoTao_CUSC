@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm đơn vị mới</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('donvi.index') }}">Danh sách đơn vị</a></div>
                <div class="breadcrumb-item">Thêm đơn vị mới</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('donvi.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mã Đơn Vị <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="MaDV" class="form-control @error('MaDV') is-invalid @enderror" 
                                               value="{{ old('MaDV') }}" required>
                                        @error('MaDV')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Đơn Vị Hiện Tại</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenDVHienTai" class="form-control" 
                                               value="{{ old('TenDVHienTai') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Đơn Vị Từng Công Tác</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenDVTungCongTac" class="form-control" 
                                               value="{{ old('TenDVTungCongTac') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                        <a href="{{ route('donvi.index') }}" class="btn btn-secondary">Hủy</a>
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