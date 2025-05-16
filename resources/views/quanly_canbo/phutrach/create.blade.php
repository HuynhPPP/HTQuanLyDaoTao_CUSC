@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm Công Việc Phụ Trách Mới</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('phutrach.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Công Việc Phụ Trách <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="CongViecPhuTrach" class="form-control @error('CongViecPhuTrach') is-invalid @enderror" 
                                               value="{{ old('CongViecPhuTrach') }}" required>
                                        @error('CongViecPhuTrach')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Miêu Tả Chi Tiết</label>
                                    <div class="col-sm-9">
                                        <textarea name="MieuTaChiTiet" class="form-control" rows="4">{{ old('MieuTaChiTiet') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                        <a href="{{ route('phutrach.index') }}" class="btn btn-secondary">Hủy</a>
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