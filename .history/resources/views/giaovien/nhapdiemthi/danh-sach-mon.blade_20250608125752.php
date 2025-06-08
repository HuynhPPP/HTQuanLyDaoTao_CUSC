@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách môn học để nhập điểm</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách môn học để nhập điểm</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @if ($giangDays->isEmpty())
                        <div class="alert alert-info">
                            Bạn chưa được phân công giảng dạy môn học nào.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Mã môn học</th>
                                        <th>Tên môn học</th>
                                        <th>Lớp</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($giangDays as $giangDay)
                                        <tr>
                                            <td>{{ $giangDay->monHoc->MaMH ?? 'N/A' }}</td>
                                            <td>{{ $giangDay->monHoc->TenMH ?? 'N/A' }}</td>
                                            <td>{{ $giangDay->lopHoc->MaLop ?? 'N/A' }}</td>
                                            <td>
                                                {{-- <a href="{{ route('giaovien.nhapdiemthi.danh-sach-lichthi', ['tenMH' => $giangDay->TenMH]) }}"
                                                    class="btn btn-primary">
                                                    Chọn lịch thi
                                                </a> --}}
                                                {{-- <a href="{{ route('giaovien.nhapdiemthi.danh-sach-lichthi', ['MaMH' => $giangDay->monHoc->MaMH]) }}"
                                                    class="btn btn-primary">
                                                    Chọn lịch thi
                                                </a> --}}
                                                <a href="{{ route('giaovien.nhapdiemthi.nhap-diem', ['MaLop' => $giangDay->lopHoc->MaLop]) }}"
                                                    class="btn btn-primary">
                                                    Chọn lịch thi
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
@endsection
