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
                                        <th>Trạng thái dự thi</th>
                                        <th>Lý do không dự thi</th>
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
                                            $diemThi = $diemThis->get($sinhVien->MaSV) ?? null;
                                        @endphp
                                        <tr>
                                            <td>{{ $sinhVien->MaSV }}</td>
                                            <td>{{ $sinhVien->HoTen }}</td>
                                            <td>
                                                <span class="badge 
                                                    @switch($sinhVien->TrangThaiDuThi)
                                                        @case('DuThi')
                                                            badge-success
                                                            @break
                                                        @case('KhongDuThi')
                                                            badge-danger
                                                            @break
                                                        @default
                                                            badge-warning
                                                    @endswitch
                                                ">
                                                    {{ $sinhVien->TrangThaiDuThi }}
                                                </span>
                                            </td>
                                            <td>{{ $sinhVien->LyDoKhongDuThi ?? 'Không có' }}</td>
                                            <td>
                                                <input type="number" 
                                                    name="diems[{{ $sinhVien->MaSV }}][DiemThucHanh]"
                                                    class="form-control" 
                                                    min="0" 
                                                    max="10" 
                                                    step="0.1"
                                                    {{ $sinhVien->TrangThaiDuThi != 'Đủ điều kiện' ? 'disabled' : '' }}
                                                    value="{{ $diemThi ? $diemThi->DiemThucHanh : '' }}">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diems[{{ $sinhVien->MaSV }}][DiemLyThuyet]"
                                                    class="form-control" 
                                                    min="0" 
                                                    max="10" 
                                                    step="0.1"
                                                    {{ $sinhVien->TrangThaiDuThi != 'Đủ điều kiện' ? 'disabled' : '' }}
                                                    value="{{ $diemThi ? $diemThi->DiemLyThuyet : '' }}">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diems[{{ $sinhVien->MaSV }}][DiemBaiTap]"
                                                    class="form-control" 
                                                    min="0" 
                                                    max="10" 
                                                    step="0.1"
                                                    {{ $sinhVien->TrangThaiDuThi != 'Đủ điều kiện' ? 'disabled' : '' }}
                                                    value="{{ $diemThi ? $diemThi->DiemBaiTap : '' }}">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                    name="diems[{{ $sinhVien->MaSV }}][Diem]"
                                                    class="form-control" 
                                                    min="0" 
                                                    max="10" 
                                                    step="0.1"
                                                    {{ $sinhVien->TrangThaiDuThi != 'Đủ điều kiện' ? 'disabled' : '' }}
                                                    value="{{ $diemThi ? $diemThi->Diem : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" 
                                                    name="diems[{{ $sinhVien->MaSV }}][GhiChu]"
                                                    class="form-control" 
                                                    value="{{ $sinhVien->GhiChu ?? '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Lưu điểm</button>
                            <a href="{{ route('giaovien.nhapdiemthi.danh-sach-mon') }}" class="btn btn-secondary ml-2">Quay lại</a>
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
