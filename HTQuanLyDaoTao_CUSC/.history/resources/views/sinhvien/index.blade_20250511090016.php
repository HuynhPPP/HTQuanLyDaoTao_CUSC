@extends('layouts.new_app.master')

@section('main-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quản lý sinh viên</h5>
                        <a href="{{ route('sinhvien.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sinh viên mới
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ tên</th>
                                        <th>Ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sinhViens as $sv)
                                        <tr>
                                            <td>{{ $sv->MaSV }}</td>
                                            <td>{{ $sv->HoTen }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                            <td>{{ $sv->GioiTinh }}</td>
                                            <td>{{ $sv->Email }}</td>
                                            <td>{{ $sv->Sdt }}</td>
                                            <td>{{ $sv->hoSo->TrangThai ?? 'Chưa có' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('sinhvien.show', $sv->MaSV) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('sinhvien.edit', $sv->MaSV) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('sinhvien.destroy', $sv->MaSV) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $sinhViens->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
