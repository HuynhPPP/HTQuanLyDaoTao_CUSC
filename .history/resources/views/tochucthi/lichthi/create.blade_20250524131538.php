@extends('layouts.new_app.master')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Lập lịch thi mới</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin lịch thi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('lichthi.store') }}" method="POST">
                            @csrf
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Môn Học</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control select2" name="TenMH" required>
                                        <option value="">Chọn môn học</option>
                                        @foreach($monHocs as $monHoc)
                                            <option value="{{ $monHoc->TenMH }}">{{ $monHoc->TenMH }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lớp</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control select2" name="MaLop" required>
                                        <option value="">Chọn lớp</option>
                                        @foreach($lopHocs as $lopHoc)
                                            <option value="{{ $lopHoc->MaLop }}">{{ $lopHoc->TenLop }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ngày Thi</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control" name="NgayThi" required>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Giờ Bắt Đầu</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="time" class="form-control" name="GioBatDau" required>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thời Lượng (phút)</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="number" class="form-control" name="ThoiLuong" required min="30" max="180">
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hình Thức Thi</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" name="HinhThucThi" required>
                                        <option value="">Chọn hình thức thi</option>
                                        <option value="Tự Luận">Tự luận</option>
                                        <option value="Trắc Nghiệm">Trắc nghiệm</option>
                                        <option value="Vấn Đáp">Bài tập lớn</option>
                                        <option value="Thực Hành">Thực hành</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phòng Thi</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control select2" name="PhongThi" required>
                                        <option value="">Chọn phòng thi</option>
                                        @foreach($phongHocs as $phongHoc)
                                            <option value="{{ $phongHoc->TenPhong }}">{{ $phongHoc->TenPhong }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <div class="col-sm-12 col-md-7 text-md-right">
                                    <button type="submit" class="btn btn-primary">Tạo lịch thi</button>
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

        $('form').submit(function(e) {
            const ngayThi = new Date($('input[name="NgayThi"]').val());
            const today = new Date();
            today.setHours(0, 0, 0, 0); // reset giờ

            if (ngayThi < today) {
                e.preventDefault();
                alert('Ngày thi không thể là ngày trong quá khứ!');
                return false;
            }

            const khungGio = $('input[name="KhungGio"]').val();
            const khungGioPattern = /^\d{2}:\d{2}\s*-\s*\d{2}:\d{2}$/;
            if (!khungGioPattern.test(khungGio)) {
                e.preventDefault();
                alert('Khung giờ phải theo định dạng HH:MM - HH:MM!');
                return false;
            }
        });
    });
</script>
@endsection
