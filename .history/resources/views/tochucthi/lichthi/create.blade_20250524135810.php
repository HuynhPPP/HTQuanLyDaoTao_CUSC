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
                            
                                {{-- Môn học --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Môn học</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="TenMH">
                                            <option value="">Chọn môn học</option>
                                            @foreach ($monHocs as $monHoc)
                                                <option value="{{ $monHoc->TenMH }}" {{ old('TenMH') == $monHoc->TenMH ? 'selected' : '' }}>
                                                    {{ $monHoc->TenMH }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('TenMH')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Lớp --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lớp</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="MaLop">
                                            <option value="">Chọn lớp</option>
                                            @foreach ($lopHocs as $lopHoc)
                                                <option value="{{ $lopHoc->MaLop }}" {{ old('MaLop') == $lopHoc->MaLop ? 'selected' : '' }}>
                                                    {{ $lopHoc->TenLop }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('MaLop')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Ngày thi --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ngày thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" class="form-control" name="NgayThi" value="{{ old('NgayThi') }}">
                                        @error('NgayThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Giờ bắt đầu --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Giờ bắt đầu</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="time" class="form-control" name="GioBatDau" value="{{ old('GioBatDau') }}">
                                        @error('GioBatDau')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Thời lượng --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thời lượng (phút)</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" class="form-control" name="ThoiLuong" value="{{ old('ThoiLuong') }}">
                                        @error('ThoiLuong')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Hình thức thi --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Hình thức Thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control" name="HinhThucThi">
                                            <option value="">Chọn hình thức thi</option>
                                            <option value="Tự luận" {{ old('HinhThucThi') == 'Tự luận' ? 'selected' : '' }}>Tự luận</option>
                                            <option value="Trắc nghiệm" {{ old('HinhThucThi') == 'Trắc nghiệm' ? 'selected' : '' }}>Trắc nghiệm</option>
                                            <option value="Bài tập lớn" {{ old('HinhThucThi') == 'Bài tập lớn' ? 'selected' : '' }}>Bài tập lớn</option>
                                            <option value="Thực hành" {{ old('HinhThucThi') == 'Thực hành' ? 'selected' : '' }}>Thực hành</option>
                                        </select>
                                        @error('HinhThucThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Phòng thi --}}
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phòng thi</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control select2" name="PhongThi">
                                            <option value="">Chọn phòng thi</option>
                                            @foreach ($phongHocs as $phongHoc)
                                                <option value="{{ $phongHoc->TenPhong }}" {{ old('PhongThi') == $phongHoc->TenPhong ? 'selected' : '' }}>
                                                    {{ $phongHoc->TenPhong }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('PhongThi')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                                {{-- Nút submit --}}
                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 offset-md-3 col-md-7">
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
