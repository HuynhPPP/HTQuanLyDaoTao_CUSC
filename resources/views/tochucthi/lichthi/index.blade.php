@extends('layouts.new_app.master')

@section('title', 'Quản Lý Lịch Thi')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý lịch thi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách lịch thi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh sách lịch thi</h4>
                    <div class="card-header-action">
                        <a href="{{ route('lichthi.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm lịch thi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Môn Thi</th>
                                    <th>Lớp</th>
                                    <th>Ngày Thi</th>
                                    <th>Thời Lượng (phút)</th>
                                    <th>Hình thức</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lichThis as $index => $lichThi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $lichThi->monHoc->TenMH ?? 'N/A' }}</td>
                                        <td>{{ $lichThi->MaLop }}</td>
                                        <td>{{ $lichThi->NgayThi }}</td>
                                        <td>{{ $lichThi->KhungGio }}</td>
                                        <td>{{ $lichThi->LoaiThi }}</td>
                                        <td>
                                            <a href="{{ route('lichthi.edit', $lichThi->MaLichThi) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('lichthi.destroy', $lichThi->MaLichThi) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa lịch thi này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
