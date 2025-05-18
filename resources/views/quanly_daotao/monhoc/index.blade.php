@extends('layouts.new_app.master')

@section('title', 'Quản Lý Môn Học')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh Sách Môn Học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Môn Học</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh Sách Môn Học</h4>
                    <div class="card-header-action">
                        <a href="{{ route('monhoc.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã Môn Học</th>
                                    <th>Tên Môn Học</th>
                                    <th>Giờ Gốc</th>
                                    <th>Giờ Triển Khai</th>
                                    <th>Loại Tiết Học</th>
                                    <th>Hình Thức Đánh Giá</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monhocs as $monhoc)
                                    <tr>
                                        <td>{{ $monhoc->MaMH }}</td>
                                        <td>{{ $monhoc->TenMH }}</td>
                                        <td>{{ $monhoc->GioGoc }}</td>
                                        <td>{{ $monhoc->GioTrienKhai }}</td>
                                        <td>
                                            @if($monhoc->TietLT) Lý thuyết @endif
                                            @if($monhoc->TietTH) Thực hành @endif
                                            @if($monhoc->TietLTvaTH) Lý thuyết và Thực hành @endif
                                        </td>
                                        <td>{{ $monhoc->MaHTDanhGia }}</td>
                                        <td>
                                            <a href="{{ route('monhoc.edit', $monhoc->TenMH) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('monhoc.destroy', $monhoc->TenMH) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-monhoc">
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
            $('.delete-monhoc').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa môn học này?',
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