@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh Sửa Đơn Vị</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('donvi.update', $donVi->MaDV) }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mã Đơn Vị</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="{{ $donVi->MaDV }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Đơn Vị Hiện Tại</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenDVHienTai" class="form-control" 
                                               value="{{ old('TenDVHienTai', $donVi->TenDVHienTai) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Đơn Vị Từng Công Tác</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenDVTungCongTac" class="form-control" 
                                               value="{{ old('TenDVTungCongTac', $donVi->TenDVTungCongTac) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
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