@extends('layouts.new_app.master')

@section('main-content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Quản lý bằng cấp cán bộ</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('student.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm mới bằng cấp cán bộ
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã bằng</th>
            <th>Tên bằng</th>
            <th>Thời gian cấp</th>
            <th>Đơn vị cấp</th>
            <th>Số hiệu</th>
            <th>Số vào sổ</th>
            <th>Thao tác</th>
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
                                                    <a href="{{ route('student.show', $sv->MaSV) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('student.edit', $sv->MaSV) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('student.destroy', $sv->MaSV) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-student"
                                                            title="Xóa">
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
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-student').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa sinh viên này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    buttons: ['Hủy', 'Xóa'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit(); // Xác nhận thì submit form
                    } else {
                        swal('Thao tác đã bị hủy.');
                    }
                });
            });
        });
    </script>
@endsection
