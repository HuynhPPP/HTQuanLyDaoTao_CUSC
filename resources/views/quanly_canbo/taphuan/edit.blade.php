@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh Sửa Tập Huấn</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('taphuan.update', $tapHuan->MaTapHuan) }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mã Tập Huấn</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="{{ $tapHuan->MaTapHuan }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tên Khóa Tập Huấn</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="TenKhoaTapHuan" class="form-control" 
                                               value="{{ old('TenKhoaTapHuan', $tapHuan->TenKhoaTapHuan) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Bắt Đầu</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="ThoiGianBatDau" class="form-control" 
                                               value="{{ old('ThoiGianBatDau', $tapHuan->ThoiGianBatDau ? date('Y-m-d', strtotime($tapHuan->ThoiGianBatDau)) : '') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Thời Gian Kết Thúc</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="ThoiGianKetThuc" class="form-control" 
                                               value="{{ old('ThoiGianKetThuc', $tapHuan->ThoiGianKetThuc ? date('Y-m-d', strtotime($tapHuan->ThoiGianKetThuc)) : '') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Địa Điểm</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="DiaDiem" class="form-control" 
                                               value="{{ old('DiaDiem', $tapHuan->DiaDiem) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
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