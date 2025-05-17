@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm học vị Mới</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('hocvi.index') }}">Danh Sách Học Vị</a></div>
                <div class="breadcrumb-item">Thêm học vị</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('hocvi.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mã Học Vị <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="MaHV"
                                            class="form-control @error('MaHV') is-invalid @enderror"
                                            value="{{ old('MaHV') }}" required>
                                        @error('MaHV')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Học Vị</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenHocVi" class="form-control"
                                            value="{{ old('TenHocVi') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Ngành Học</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="NganhHoc" class="form-control"
                                            value="{{ old('NganhHoc') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Chuyên Ngành</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="ChuyenNganh" class="form-control"
                                            value="{{ old('ChuyenNganh') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cơ Sở Đào Tạo</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="CoSoDaoTao" class="form-control"
                                            value="{{ old('CoSoDaoTao') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Năm Cấp</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="NamCap" class="form-control"
                                            value="{{ old('NamCap') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Hình Thức Đào Tạo</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="HinhThucDaoTao" class="form-control"
                                            value="{{ old('HinhThucDaoTao') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                        <a href="{{ route('hocvi.index') }}" class="btn btn-secondary">Hủy</a>
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
