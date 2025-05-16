@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản Lý Học Vị</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh Sách Học Vị</h4>
                            <div class="card-header-action">
                                <a href="{{ route('hocvi.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm Học Vị Mới
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã Học Vị</th>
                                            <th>Tên Học Vị</th>
                                            <th>Ngành Học</th>
                                            <th>Chuyên Ngành</th>
                                            <th>Cơ Sở Đào Tạo</th>
                                            <th>Năm Cấp</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hocVis as $hocVi)
                                            <tr>
                                                <td>{{ $hocVi->MaHV }}</td>
                                                <td>{{ $hocVi->TenHocVi }}</td>
                                                <td>{{ $hocVi->NganhHoc }}</td>
                                                <td>{{ $hocVi->ChuyenNganh }}</td>
                                                <td>{{ $hocVi->CoSoDaoTao }}</td>
                                                <td>{{ $hocVi->NamCap ? date('Y', strtotime($hocVi->NamCap)) : '' }}</td>
                                                <td>
                                                    <a href="{{ route('hocvi.edit', $hocVi->MaHV) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('hocvi.destroy', $hocVi->MaHV) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            >
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
            $('.delete-hocvi').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa học vị này?',
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
