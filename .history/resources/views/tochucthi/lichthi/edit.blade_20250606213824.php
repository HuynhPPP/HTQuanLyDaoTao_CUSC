@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Chỉnh sửa lịch thi</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('lichthi.update', $lichThi->MaLichThi) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lớp</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="MaLop">
                                            <option value="">Chọn lớp</option>
                                            @foreach ($lopHocs as $lopHoc)
                                                <option value="{{ $lopHoc->MaLop }}"
                                                    {{ $lichThi->MaLop == $lopHoc->MaLop ? 'selected' : '' }}>
                                                    {{ $lopHoc->TenLop }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaLop')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Môn học</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="MaMH">
                                            <option value="">Chọn môn học</option>
                                            @foreach ($monHocs as $monHoc)
                                                <option value="{{ $monHoc->MaMH }}"
                                                    {{ $lichThi->MaMH == $monHoc->MaMH ? 'selected' : '' }}>
                                                    {{ $monHoc->TenMH }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaMH')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ngày Thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" class="form-control" name="NgayThi"
                                            value="{{ $lichThi->NgayThi }}">
                                        @error('NgayThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Giờ bắt đầu</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="time" class="form-control" name="GioBatDau"
                                            value="{{ $lichThi->GioBatDau ?? '' }}">
                                        @error('GioBatDau')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thời gian thi
                                        (phút)</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" class="form-control" name="ThoiLuong"
                                            value="{{ $lichThi->ThoiLuong ?? '' }}">
                                            @error('ThoiLuong')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hình thức
                                        thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control" name="HinhThucThi">
                                            <option value="">Chọn hình thức thi</option>
                                            <option value="Tự luận"
                                                {{ $lichThi->HinhThucThi == 'Tự luận' ? 'selected' : '' }}>Tự luận</option>
                                            <option value="Trắc nghiệm"
                                                {{ $lichThi->HinhThucThi == 'Trắc nghiệm' ? 'selected' : '' }}>Trắc nghiệm
                                            </option>
                                            <option value="Bài tập lớn"
                                                {{ $lichThi->HinhThucThi == 'Bài tập lớn' ? 'selected' : '' }}>Bài tập lớn
                                            </option>
                                            <option value="Thực hành"
                                                {{ $lichThi->HinhThucThi == 'Thực hành' ? 'selected' : '' }}>Thực hành
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phòng thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="PhongThi">
                                            <option value="">Chọn phòng thi</option>
                                            @foreach ($phongHocs as $phongHoc)
                                                <option value="{{ $phongHoc->TenPhong }}"
                                                    {{ $lichThi->PhongThi == $phongHoc->TenPhong ? 'selected' : '' }}>
                                                    {{ $phongHoc->TenPhong }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lần thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" class="form-control" name="LanThi"
                                            value="{{ $lichThi->LanThi ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
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
