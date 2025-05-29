@extends('layouts.new_app.master')

@section('title', ' Lịch Thi')

@section('main-content')
<section class="section">
    <div class="section-header">
        <h1></h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Danh Sách Lịch Thi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-lich-thi">
                        <thead>
                            <tr>
                                <th>Môn Thi</th>
                                <th>Lớp</th>
                                <th>Ngày Thi</th>
                                <th>Giờ Thi</th>
                                <th>Phòng Thi</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($danhSachLichThi as $lichThi)
                                <tr>
                                    <td>{{ $lichThi->TenMH }}</td>
                                    <td>{{ $lichThi->MaLop }}</td>
                                    <td>{{ $lichThi->NgayThi }}</td>
                                    <td>{{ $lichThi->KhungGio }}</td>
                                    <td>{{ $lichThi->PhongThi }}</td>
                                    <td>
                                        <a href="{{ route('sinhvien.duthi.danh-sach', $lichThi->MaLichThi) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-list"></i> Danh Sách Dự Thi
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