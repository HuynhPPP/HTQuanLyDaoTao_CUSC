@extends('layouts.new_app.master')

@section('main-content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Quản lý sinh viên</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã SV</th>
                                            <th>Họ Tên</th>
                                            <th>Ngày Sinh</th>
                                            <th>Giới Tính</th>
                                            <th>Số CCCD</th>
                                            <th>Email</th>
                                            <th>SĐT</th>
                                            <th>Địa Chỉ</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sv)
                                            <tr>
                                                <td>{{ $sv->MaSV }}</td>
                                                <td>{{ $sv->HoTen }}</td>
                                                <td>{{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                                <td>{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td>{{ $sv->SoCCCD }}</td>
                                                <td>{{ $sv->Email }}</td>
                                                <td>{{ $sv->Sdt }}</td>
                                                <td>{{ $sv->DiaChi }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('student.show', $sv->MaSV) }}"
                                                            class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href=""
                                                            class="btn btn-warning btn-sm" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action=""
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
