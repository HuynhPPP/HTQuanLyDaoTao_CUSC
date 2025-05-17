@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý tập huấn</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Quản lý tập huấn</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh Sách Tập Huấn</h4>
                            <div class="card-header-action">
                                <a href="{{ route('taphuan.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm Tập Huấn Mới
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã Tập Huấn</th>
                                            <th>Tên Khóa Tập Huấn</th>
                                            <th>Thời Gian Bắt Đầu</th>
                                            <th>Thời Gian Kết Thúc</th>
                                            <th>Địa Điểm</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tapHuans as $tapHuan)
                                            <tr>
                                                <td>{{ $tapHuan->MaTapHuan }}</td>
                                                <td>{{ $tapHuan->TenKhoaTapHuan }}</td>
                                                <td>{{ $tapHuan->ThoiGianBatDau ? date('d/m/Y', strtotime($tapHuan->ThoiGianBatDau)) : '' }}</td>
                                                <td>{{ $tapHuan->ThoiGianKetThuc ? date('d/m/Y', strtotime($tapHuan->ThoiGianKetThuc)) : '' }}</td>
                                                <td>{{ $tapHuan->DiaDiem }}</td>
                                                <td>
                                                    <a href="{{ route('taphuan.edit', $tapHuan->MaTapHuan) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('taphuan.destroy', $tapHuan->MaTapHuan) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-taphuan">
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
            $('.delete-taphuan').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa tập huấn này?',
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