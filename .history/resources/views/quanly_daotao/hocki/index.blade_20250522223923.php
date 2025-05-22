@extends('layouts.new_app.master')

@section('title', 'Quản Lý Học Kỳ')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách học kỳ</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Học kỳ</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh sách học kỳ</h4>
                    <div class="card-header-action">
                        <a href="{{ route('hocki.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã Học Kỳ</th>
                                    <th>Tên Học Kỳ</th>
                                    <th>Tổng Giờ Gốc</th>
                                    <th>Tổng Giờ Triển Khai</th>
                                    <th>Chương Trình</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hockis as $hocki)
                                    <tr>
                                        <td>{{ $hocki->MaHK }}</td>
                                        <td>{{ $hocki->TenHK }}</td>
                                        <td>{{ $hocki->TongGioGoc }}</td>
                                        <td>{{ $hocki->TongGioTrienKhai }}</td>
                                        <td>{{ $hocki->chuongTrinh->TenChuongTrinh }}</td>
                                        <td>
                                            <a href="{{ route('hocki.edit', $hocki->MaHK) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hocki.destroy', $hocki->MaHK) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-hocki">
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
            $('.delete-hocki').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa học kỳ này?',
                    text: 'Dữ liệu đã xóa sẽ không thể khôi phục!',
                    icon: 'warning',
                    buttons: ['Hủy', 'Xóa'],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal('Thao tác đã bị hủy.');
                    }
                });
            });
        });
    </script>
@endsection