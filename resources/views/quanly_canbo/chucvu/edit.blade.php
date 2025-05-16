@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh Sửa Chức Vụ</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('chucvu.update', $chucVu->TenChucVu) }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Chức Vụ</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="{{ $chucVu->TenChucVu }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Bắt Đầu</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="ThoiGianBatDauCV" class="form-control" 
                                               value="{{ old('ThoiGianBatDauCV', $chucVu->ThoiGianBatDauCV) }}" 
                                               placeholder="Ví dụ: 01/2023">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Kết Thúc</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="ThoiGianKTCV" class="form-control" 
                                               value="{{ old('ThoiGianKTCV', $chucVu->ThoiGianKTCV) }}" 
                                               placeholder="Ví dụ: 12/2023">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                        <a href="{{ route('chucvu.index') }}" class="btn btn-secondary">Hủy</a>
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