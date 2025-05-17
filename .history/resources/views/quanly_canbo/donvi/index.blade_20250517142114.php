@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản Lý Đơn Vị</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Quản Lý Đơn Vị</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh Sách Đơn Vị</h4>
                            <div class="card-header-action">
                                <a href="{{ route('donvi.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm Đơn Vị Mới
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã Đơn Vị</th>
                                            <th>Tên Đơn Vị Hiện Tại</th>
                                            <th>Tên Đơn Vị Từng Công Tác</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($donVis as $donVi)
                                            <tr>
                                                <td>{{ $donVi->MaDV }}</td>
                                                <td>{{ $donVi->TenDVHienTai }}</td>
                                                <td>{{ $donVi->TenDVTungCongTac }}</td>
                                                <td>
                                                    <a href="{{ route('donvi.edit', $donVi->MaDV) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('donvi.destroy', $donVi->MaDV) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-donvi">
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
            $('.delete-donvi').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa đơn vị này?',
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