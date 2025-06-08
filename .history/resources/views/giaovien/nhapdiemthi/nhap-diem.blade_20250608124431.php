@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Nhập điểm thi môn {{ $lichThi->monHoc->TenMH }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}">Danh sách môn
                        học để nhập điểm</a></div>
                <div class="breadcrumb-item">Danh sách lịch thi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách sinh viên dự thi</h4>
                    <div class="card-header-action">
                        <span class="badge badge-primary mr-2">
                            <i class="fas fa-calendar-alt"></i>
                            Ngày Thi: {{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') }}
                        </span>
                        <span class="badge badge-info">
                            <i class="fas fa-door-open"></i>
                            Phòng Thi: {{ $lichThi->PhongThi }}
                        </span>
                        <span class="badge badge-primary mr-2">
                            <i class="fas fa-chalkboard"></i>
                            Hình thức thi: {{ $lichThi->HinhThucThi }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('giaovien.nhapdiemthi.luu-diem', ['maLichThi' => $lichThi->MaLichThi]) }}"
                        method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Mã sinh viên</th>
                                        <th>Tên sinh viên</th>
                                        <th>Lần thi</th>
                                        @switch($lichThi->HinhThucThi)
                                            @case('Trắc nghiệm')
                                                <th>Điểm lý thuyết - trắc nghiệm</th>
                                            @break

                                            @case('Thực hành')
                                                <th>Điểm thực hành</th>
                                            @break

                                            @case('Dự án')
                                                <th>Điểm dự án</th>
                                            @break

                                            @default
                                                <th>Điểm tổng</th>
                                        @endswitch
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sinhViens as $sinhVien)
                                        @php
                                            $diemThi = $diemThis->get($sinhVien->MaSV) ?? null;
                                        @endphp
                                        <tr>
                                            <td>{{ $sinhVien->MaSV }}</td>
                                            <td>{{ $sinhVien->HoTen }}</td>
                                            <td>
                                                <input type="number" name="diems[{{ $sinhVien->MaSV }}][LanThi]"
                                                    class="form-control" value="{{ $diemThi ? $diemThi->LanThi : 1 }}"
                                                    readonly>
                                            </td>
                                            @switch($lichThi->HinhThucThi)
                                                @case('Trắc nghiệm')
                                                    <td>
                                                        <input type="number" name="diems[{{ $sinhVien->MaSV }}][DiemLyThuyet]"
                                                            class="form-control"
                                                            value="{{ $diemThi ? $diemThi->DiemLyThuyet : '' }}">
                                                    </td>
                                                @break

                                                @case('Thực hành')
                                                    <td>
                                                        <input type="number" name="diems[{{ $sinhVien->MaSV }}][DiemThucHanh]"
                                                            class="form-control"
                                                            value="{{ $diemThi ? $diemThi->DiemThucHanh : '' }}">
                                                    </td>
                                                @break

                                                @case('Dự án')
                                                    <td>
                                                        <input type="number" name="diems[{{ $sinhVien->MaSV }}][DiemDuAn]"
                                                            class="form-control" value="{{ $diemThi ? $diemThi->DiemDuAn : '' }}">
                                                    </td>
                                                @break

                                                @default
                                                    <td>
                                                        <input type="number" name="diems[{{ $sinhVien->MaSV }}][Diem]"
                                                            class="form-control" value="{{ $diemThi ? $diemThi->Diem : '' }}">
                                                    </td>
                                            @endswitch
                                            <td>
                                                <input type="text" name="diems[{{ $sinhVien->MaSV }}][GhiChu]"
                                                    class="form-control" value="{{ $diemThi ? $diemThi->GhiChu : '' }}">
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
