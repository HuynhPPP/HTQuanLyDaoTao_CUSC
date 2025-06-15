@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Nhập điểm thi lớp {{ $lophoc }} - Môn {{ $monHoc->TenMH ?? 'Chưa xác định' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}">Danh sách môn học để nhập điểm</a>
                </div>
                <div class="breadcrumb-item">Nhập điểm lớp</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách sinh viên dự thi</h4>
                    <div class="card-header-action">
                        <span class="badge badge-primary mr-2">
                            <i class="fas fa-chalkboard"></i>
                            Mã Lớp: {{ $lop->MaLop }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('giaovien.nhapdiemthi.luu-diem', ['lophoc' => $lop->MaLop]) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Mã sinh viên</th>
                                        <th>Tên sinh viên</th>
                                        <th>Điểm thực hành</th>
                                        <th>Điểm lý thuyết</th>
                                        <th>Điểm bài tập</th>
                                        <th>Điểm tổng</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sinhViens as $sinhVien)
                                        @php
                                            $maSV = $sinhVien->MaSV;
                                            $diemThi = $diemThis->get($maSV) ?? null;
                                        @endphp
                                        <tr>
                                            <td>{{ $maSV }}</td>
                                            <td>{{ $sinhVien->HoTen }}</td>

                                            {{-- Điểm thực hành --}}
                                            <td>
                                                <input type="number" name="diems[{{ $maSV }}][DiemThucHanh]"
                                                    class="form-control @error('diems.' . $maSV . '.DiemThucHanh') is-invalid @enderror"
                                                    min="0" max="100" step="10"
                                                    {{ $sinhVien->TrangThaiDuThi != 'DuThi' ? 'disabled' : '' }}
                                                    value="{{ old('diems.' . $maSV . '.DiemThucHanh', $diemThi->DiemThucHanh ?? '') }}">
                                                @error('diems.' . $maSV . '.DiemThucHanh')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            {{-- Điểm lý thuyết --}}
                                            <td>
                                                <input type="number" name="diems[{{ $maSV }}][DiemLyThuyet]"
                                                    class="form-control @error('diems.' . $maSV . '.DiemLyThuyet') is-invalid @enderror"
                                                    min="0" max="100" step="10"
                                                    {{ $sinhVien->TrangThaiDuThi != 'DuThi' ? 'disabled' : '' }}
                                                    value="{{ old('diems.' . $maSV . '.DiemLyThuyet', $diemThi->DiemLyThuyet ?? '') }}">
                                                @error('diems.' . $maSV . '.DiemLyThuyet')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            {{-- Điểm bài tập --}}
                                            <td>
                                                <input type="number" name="diems[{{ $maSV }}][DiemBaiTap]"
                                                    class="form-control @error('diems.' . $maSV . '.DiemBaiTap') is-invalid @enderror"
                                                    min="0" max="100" step="10"
                                                    {{ $sinhVien->TrangThaiDuThi != 'DuThi' ? 'disabled' : '' }}
                                                    value="{{ old('diems.' . $maSV . '.DiemBaiTap', $diemThi->DiemDuAn ?? '') }}">
                                                @error('diems.' . $maSV . '.DiemBaiTap')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            {{-- Điểm tổng --}}
                                            <td>
                                                <input type="number" name="diems[{{ $maSV }}][Diem]"
                                                    class="form-control @error('diems.' . $maSV . '.Diem') is-invalid @enderror"
                                                    min="0" max="100" step="10"
                                                    {{ $sinhVien->TrangThaiDuThi != 'DuThi' ? 'disabled' : '' }}
                                                    value="{{ old('diems.' . $maSV . '.Diem', $diemThi->DiemTong ?? '') }}">
                                                @error('diems.' . $maSV . '.Diem')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            {{-- Ghi chú --}}
                                            <td>
                                                <input type="text" name="diems[{{ $maSV }}][GhiChu]"
                                                    class="form-control @error('diems.' . $maSV . '.GhiChu') is-invalid @enderror"
                                                    value="{{ old('diems.' . $maSV . '.GhiChu', $diemThi->GhiChu ?? '') }}">
                                                @error('diems.' . $maSV . '.GhiChu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu điểm</button>
                            <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}" class="btn btn-secondary ml-2">Quay
                                lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('input[name^="diems"]');

        // Load dữ liệu đã lưu
        inputs.forEach(input => {
            const storedValue = localStorage.getItem(input.name);
            if (storedValue !== null && input.value === "") {
                input.value = storedValue;
            }

            // Ghi dữ liệu khi người dùng nhập
            input.addEventListener('input', () => {
                localStorage.setItem(input.name, input.value);
            });
        });

        // Xóa dữ liệu localStorage nếu form gửi thành công
        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            inputs.forEach(input => {
                localStorage.removeItem(input.name);
            });
        });
    });
</script>
    <script>
        $(document).ready(function() {
            // Validate điểm nhập vào
            $('input[type="number"]').on('input', function() {
                let value = parseFloat($(this).val());
                let min = parseFloat($(this).attr('min'));
                let max = parseFloat($(this).attr('max'));

                if (isNaN(value) || value < min || value > max) {
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
