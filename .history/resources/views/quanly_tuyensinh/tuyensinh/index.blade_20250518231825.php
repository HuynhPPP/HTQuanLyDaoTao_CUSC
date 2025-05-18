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

<!-- Modal Thêm Đợt Tuyển Sinh -->
<div class="modal fade" id="modalThemDotTS" tabindex="-1" role="dialog" aria-labelledby="modalThemDotTSLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalThemDotTSLabel">Thêm đợt tuyển sinh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tuyensinh.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Năm Tuyển Sinh</label>
                        <input type="number" class="form-control" name="NamTS" required min="2000" max="2100">
                    </div>
                    <div class="form-group">
                        <label>Đợt</label>
                        <input type="number" class="form-control" name="DotTS" required min="1" max="4">
                    </div>
                    <div class="form-group">
                        <label>Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" name="NgayBatDau" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày Kết Thúc</label>
                        <input type="date" class="form-control" name="NgayKetThuc" required>
                    </div>
                    <div class="form-group">
                        <label>Chỉ Tiêu</label>
                        <input type="number" class="form-control" name="ChiTieuTS" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection