@extends('layouts.new_app.master')

@section('title', 'Quản Lý Chương Trình Đào Tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh Sách Chương Trình Đào Tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Chương Trình Đào Tạo</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh Sách Chương Trình</h4>
                    <div class="card-header-action">
                        <a href="{{ route('chuongtrinh.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã Chương Trình</th>
                                    <th>Tên Chương Trình</th>
                                    <th>Phiên Bản</th>
                                    <th>Ngày Triển Khai</th>
                                    <th>Khóa Đào Tạo</th>
                                    <th>Thời Gian Đào Tạo</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chuongTrinhs as $chuongTrinh)
                                    <tr>
                                        <td>{{ $chuongTrinh->MaChuongTrinh }}</td>
                                        <td>{{ $chuongTrinh->TenChuongTrinh }}</td>
                                        <td>{{ $chuongTrinh->PhienBan }}</td>
                                        <td>{{ $chuongTrinh->NgayTrienKhaiPB ? date('d/m/Y', strtotime($chuongTrinh->NgayTrienKhaiPB)) : 'Chưa xác định' }}
                                        </td>
                                        <td>{{ $chuongTrinh->TenKhoaDaoTao }}</td>
                                        <td>{{ $chuongTrinh->ThoiGianDaoTao }}</td>
                                        <td>
                                            <a href="{{ route('chuongtrinh.edit', $chuongTrinh->MaChuongTrinh) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('chuongtrinh.destroy', $chuongTrinh->MaChuongTrinh) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-chuongtrinh"
                                                    onclick="return confirm('Bạn có chắc muốn xóa?')">
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

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-chuongtrinh').click(function(e) {
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
