@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách sinh viên</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4></h4>
                            <div class="card-header-action">
                                <div class="" role="group" aria-label="Thao tác">
                                    <a href="{{ route('ldap.account.list') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list-alt mr-1"></i>Danh sách tài khoản sinh viên
                                    </a>
                                    <a href="{{ route('student.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i>Thêm sinh viên
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cogs mr-1"></i>Thao tác khác
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('dongbo.taikhoan.ldap') }}">
                                                <i class="fas fa-sync mr-1"></i>Tự tạo tài khoản cho sinh viên
                                            </a>
                                            <a class="dropdown-item" href="{{ route('ldap.kiem-tra-dong-bo') }}">
                                                <i class="fas fa-server mr-1"></i>Kiểm tra kết nối
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Mã SV</th>
                                            <th class="text-nowrap">Họ Tên</th>
                                            <th class="text-nowrap">Ngày Sinh</th>
                                            <th class="text-nowrap">Giới Tính</th>
                                            <th class="text-nowrap">Số CCCD</th>
                                            <th class="text-nowrap">Email CUSC</th>
                                            <th class="text-nowrap">SĐT</th>
                                            <th class="text-nowrap">Địa Chỉ</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sv)
                                            <tr>
                                                <td class="text-nowrap">{{ $sv->MaSV }}</td>
                                                <td class="text-nowrap">{{ $sv->HoTen }}</td>
                                                <td class="text-nowrap">
                                                    {{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                                <td class="text-nowrap">{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td class="text-nowrap">{{ $sv->SoCCCD }}</td>
                                                <td class="text-nowrap">{{ $sv->EmailCUSC }}</td>
                                                <td class="text-nowrap">{{ $sv->Sdt }}</td>
                                                <td class="text-truncate" style="max-width: 200px;">{{ $sv->DiaChi }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('student.show', $sv->MaSV) }}"
                                                            class="btn btn-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('student.destroy', $sv->MaSV) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger delete-student"
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

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-student').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: 'Bạn có chắc chắn muốn xóa sinh viên này? Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
