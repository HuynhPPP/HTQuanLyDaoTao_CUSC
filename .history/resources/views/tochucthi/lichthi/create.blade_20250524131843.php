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
                                    <label for="TenMH">Môn học</label>
                                    <select name="TenMH" id="TenMH" class="form-control select2">
                                        <option value="">-- Chọn môn học --</option>
                                        @foreach ($monHocs as $monHoc)
                                            <option value="{{ $monHoc->TenMH }}"
                                                {{ old('TenMH') == $monHoc->TenMH ? 'selected' : '' }}>
                                                {{ $monHoc->TenMH }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('TenMH')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Lớp --}}
                                <div class="form-group row mb-4">
                                    <label for="MaLop">Lớp</label>
                                    <select name="MaLop" id="MaLop" class="form-control select2">
                                        <option value="">-- Chọn lớp học --</option>
                                        @foreach ($lopHocs as $lop)
                                            <option value="{{ $lop->MaLop }}"
                                                {{ old('MaLop') == $lop->MaLop ? 'selected' : '' }}>
                                                {{ $lop->TenLop }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaLop')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Ngày thi --}}
                                <div class="form-group row mb-4">
                                    <label for="NgayThi">Ngày thi</label>
                                    <input type="date" name="NgayThi" id="NgayThi" class="form-control"
                                        value="{{ old('NgayThi') }}">
                                    @error('NgayThi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Khung giờ --}}
                                <div class="form-group row mb-4">
                                    <label for="KhungGio">Khung giờ (ví dụ: 08:00 - 10:00)</label>
                                    <input type="text" name="KhungGio" id="KhungGio" class="form-control"
                                        placeholder="HH:MM - HH:MM" value="{{ old('KhungGio') }}">
                                    @error('KhungGio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Loại thi --}}
                                <div class="form-group row mb-4">
                                    <label for="LoaiThi">Loại thi</label>
                                    <select name="LoaiThi" id="LoaiThi" class="form-control">
                                        <option value="">-- Chọn loại thi --</option>
                                        <option value="Lý thuyết" {{ old('LoaiThi') == 'Lý thuyết' ? 'selected' : '' }}>Lý
                                            thuyết</option>
                                        <option value="Thực hành" {{ old('LoaiThi') == 'Thực hành' ? 'selected' : '' }}>
                                            Thực hành</option>
                                        <option value="Bài tập lớn"
                                            {{ old('LoaiThi') == 'Bài tập lớn' ? 'selected' : '' }}>Bài tập lớn</option>
                                    </select>
                                    @error('LoaiThi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Phòng thi --}}
                                <div class="form-group row mb-4">
                                    <label for="PhongThi">Phòng thi</label>
                                    <select name="PhongThi" id="PhongThi" class="form-control select2">
                                        <option value="">-- Chọn phòng thi --</option>
                                        @foreach ($phongHocs as $phong)
                                            <option value="{{ $phong->TenPhong }}"
                                                {{ old('PhongThi') == $phong->TenPhong ? 'selected' : '' }}>
                                                {{ $phong->TenPhong }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('PhongThi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Ghi chú --}}
                                <div class="form-group row mb-4">
                                    <label for="GhiChu">Ghi chú (nếu có)</label>
                                    <textarea name="GhiChu" id="GhiChu" rows="3" class="form-control" placeholder="Nhập ghi chú">{{ old('GhiChu') }}</textarea>
                                    @error('GhiChu')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Nút submit --}}
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">Tạo lịch thi</button>
                                    <a href="{{ route('lichthi.index') }}" class="btn btn-secondary">Hủy</a>
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
