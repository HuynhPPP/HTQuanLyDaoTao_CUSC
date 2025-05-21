@extends('layouts.new_app.master')

@section('main-content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Quản lý sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                    <div class="breadcrumb-item">Danh sách sinh viên</div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('student.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm sinh viên 
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Mã SV</th>
                                            <th class="text-nowrap">Họ Tên</th>
                                            <th class="text-nowrap">Ngày Sinh</th>
                                            <th class="text-nowrap">Giới Tính</th>
                                            <th class="text-nowrap">Số CCCD</th>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">SĐT</th>
                                            <th class="text-nowrap">Địa Chỉ</th>
                                            <th class="text-nowrap">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sinhViens as $index => $sv)
                                            <tr>
                                                <td class="text-nowrap">{{ $sv->MaSV }}</td>
                                                <td class="text-nowrap">{{ $sv->HoTen }}</td>
                                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}</td>
                                                <td class="text-nowrap">{{ $sv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td class="text-nowrap">{{ $sv->SoCCCD }}</td>
                                                <td class="text-wrap" style="max-width: 200px;">
                                                    <div class="text-truncate">{{ $sv->Email }}</div>
                                                </td>
                                                <td class="text-nowrap">{{ $sv->Sdt }}</td>
                                                <td class="text-wrap" style="max-width: 250px;">
                                                    <div class="text-truncate">{{ $sv->DiaChi }}</div>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="{{ route('student.show', $sv->MaSV) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- <a href="{{ route('student.edit', $sv->MaSV) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a> --}}
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
