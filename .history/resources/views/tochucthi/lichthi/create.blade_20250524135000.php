@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Tạo Lịch Thi Mới</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Thông Tin Lịch Thi</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('lichthi.store') }}" method="POST">
                                @csrf
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Môn Học</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="TenMH">
                                            <option value="">Chọn môn học</option>
                                            @foreach ($monHocs as $monHoc)
                                                <option value="{{ $monHoc->TenMH }}">{{ $monHoc->TenMH }}</option>
                                            @endforeach
                                        </select>
                                        @error('TenMH')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lớp</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="MaLop">
                                            <option value="">Chọn lớp</option>
                                            @foreach ($lopHocs as $lopHoc)
                                                <option value="{{ $lopHoc->MaLop }}">{{ $lopHoc->TenLop }}</option>
                                            @endforeach
                                        </select>
                                        @error('MaLop')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ngày Thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" class="form-control" name="NgayThi">
                                        @error('NgayThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Giờ Bắt Đầu</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="time" class="form-control" name="GioBatDau">
                                        @error('GioBatDau')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thời Lượng
                                        (phút)</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" class="form-control" name="ThoiLuong">
                                        @error('ThoiLuong')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hình Thức
                                        Thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control" name="HinhThucThi" required>
                                            <option value="">Chọn hình thức thi</option>
                                            <option value="Tự luận">Tự luận</option>
                                            <option value="Trắc nghiệm">Trắc nghiệm</option>
                                            <option value="Bài tập lớn">Bài tập lớn</option>
                                            <option value="Thực hành">Thực hành</option>
                                        </select>
                                        @error('HinhThucThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phòng Thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="PhongThi" required>
                                            <option value="">Chọn phòng thi</option>
                                            @foreach ($phongHocs as $phongHoc)
                                                <option value="{{ $phongHoc->TenPhong }}">{{ $phongHoc->TenPhong }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 offset-md-3 col-md-7">
                                        <button type="submit" class="btn btn-primary">Tạo Lịch Thi</button>
                                        <a href="{{ route('lichthi.index') }}" class="btn btn-light ml-2">Hủy</a>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Validate form trước khi submit
            $('form').submit(function(e) {
                var ngayThi = new Date($('input[name="NgayThi"]').val());
                var today = new Date();
                today.setHours(0, 0, 0, 0);

                if (ngayThi < today) {
                    e.preventDefault();
                    alert('Ngày thi không thể là ngày trong quá khứ!');
                    return false;
                }
            });
        });
    </script>
@endsection
