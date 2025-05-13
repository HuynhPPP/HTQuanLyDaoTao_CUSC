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
                            <a href="{{ route('bangcapcanbo.create') }}" class="btn btn-primary">
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
                                        @foreach ($bangcaps as $bc)
                                            <tr>
                                                <td>{{ $bc->MaBang }}</td>
                                                <td>{{ $bc->TenBang }}</td>
                                                <td>{{ $bc->ThoiGianCap }}</td>
                                                <td>{{ $bc->DonViCap }}</td>
                                                <td>{{ $bc->SoHieu }}</td>
                                                <td>{{ $bc->SoVaoSo }}</td>
                                                <td>
                                                    <a href="{{ route('bangcapcanbo.show', $bc->MaBang) }}"
                                                        class="btn btn-info btn-sm">Xem</a>
                                                    <a href="{{ route('bangcapcanbo.edit', $bc->MaBang) }}"
                                                        class="btn btn-warning btn-sm">Sửa</a>
                                                    <form action="{{ route('bangcapcanbo.destroy', $bc->MaBang) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-bangcap">Xóa</button>
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
            $('.delete-bangcap').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa bằng cấp này?',
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
