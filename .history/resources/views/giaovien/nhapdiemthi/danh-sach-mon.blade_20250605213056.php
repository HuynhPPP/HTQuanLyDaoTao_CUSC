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
                    @if ($monHocs->isEmpty())
                        <div class="alert alert-info">
                            Bạn chưa được phân công giảng dạy môn học nào.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Mã Môn Học</th>
                                        <th>Tên Môn Học</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monHocs as $monHoc)
                                        <tr>
                                            <td></td>
                                            <td>{{ $monHoc->MaMH }}</td>
                                            <td>{{ $monHoc->TenMH }}</td>
                                            <td>
                                                <a href="{{ route('giaovien.nhapdiemthi.danh-sach-lichthi', ['tenMH' => $monHoc->TenMH]) }}"
                                                    class="btn btn-primary">
                                                    Chọn Lịch Thi
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
