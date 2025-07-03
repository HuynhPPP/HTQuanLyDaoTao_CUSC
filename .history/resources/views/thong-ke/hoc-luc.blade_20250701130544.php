@extends('layouts.new_app.master')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Tổng kết học lực – Lớp {{ $lop->MaLop }} - {{ $lop->loaidaotao->TenChuongTrinh }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Bảng tổng kết học lực</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>Mã SV</th>
                                <th>Họ tên</th>
                                <th>Điểm TB</th>
                                <th>Xếp loại</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ketQua as $row)
                                <tr>
                                    <td>{{ $row->MaSV }}</td>
                                    <td>{{ $row->HoTen }}</td>
                                    <td>{{ number_format($row->DiemTB, 2) }}</td>
                                    <td>{{ $row->XepLoai }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
