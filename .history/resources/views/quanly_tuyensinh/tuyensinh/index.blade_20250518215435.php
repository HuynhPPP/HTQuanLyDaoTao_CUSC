@extends('layouts.new_app.master')

@section('title', 'Quản Lý Tuyển Sinh')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Quản Lý Tuyển Sinh</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Danh Sách Đợt Tuyển Sinh</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalThemDotTS">
                        <i class="fas fa-plus"></i> Thêm Đợt Tuyển Sinh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>Mã TS</th>
                                <th>Năm</th>
                                <th>Đợt</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Chỉ Tiêu</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dotTuyenSinh as $dot)
                            <tr>
                                <td>{{ $dot->MaTS }}</td>
                                <td>{{ $dot->NamTS }}</td>
                                <td>{{ $dot->DotTS }}</td>
                                <td>{{ $dot->NgayBatDau }}</td>
                                <td>{{ $dot->NgayKetThuc }}</td>
                                <td>{{ $dot->ChiTieuTS }}</td>
                                <td>
                                    <a href="{{ route('tuyensinh.danhsach_hoso', $dot->MaTS) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-list"></i> Xem Hồ Sơ
                                    </a>
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