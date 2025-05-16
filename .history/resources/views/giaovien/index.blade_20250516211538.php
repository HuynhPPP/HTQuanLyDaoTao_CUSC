@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý Giáo Viên</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Danh sách Giáo Viên</h4>
                            <a href="{{ route('giaovien.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm Giáo Viên
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã GV</th>
                                            <th>Họ Tên</th>
                                            <th>Giới Tính</th>
                                            <th>Email</th>
                                            <th>Loại GV</th>
                                            <th>Học Vị</th>
                                            <th>Chức Vụ</th>
                                            <th>Đơn Vị</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giaoviens as $gv)
                                            <tr>
                                                <td>{{ $gv->MaGV }}</td>
                                                <td>{{ $gv->HoTenGV }}</td>
                                                <td>{{ $gv->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td>{{ $gv->Email }}</td>
                                                <td>{{ $gv->LoaiGV == 'CoHuu' ? 'Cơ Hữu' : 'Mời Giảng' }}</td>
                                                <td>{{ optional($gv->hocvi)->TenHocVi ?? 'Chưa có' }}</td>
                                                <td>{{ optional($gv->chucvu)->TenChucVu ?? 'Chưa có' }}</td>
                                                <td>{{ optional($gv->donvi)->TenDVHienTai ?? 'Chưa có' }}</td>
                                                <td>
                                                    <a href="{{ route('giaovien.show', $gv->MaGV) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('giaovien.edit', $gv->MaGV) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('giaovien.destroy', $gv->MaGV) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-giaovien">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination mt-4 justify-content-center">
                                {{ $giaoviens->links() }}
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
            $('.delete-giaovien').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa giáo viên này?',
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