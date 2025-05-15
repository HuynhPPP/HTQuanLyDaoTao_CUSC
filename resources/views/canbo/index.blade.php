@extends('layouts.new_app.master')

@section('main-content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Quản lý cán bộ</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm cán bộ mới
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã CB</th>
                                            <th>Họ Tên</th>
                                            <th>Giới Tính</th>
                                            <th>Email</th>
                                            <th>SĐT</th>
                                            <th>Học vị</th>
                                            <th>Chức vụ</th>
                                            <th>Đơn vị</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($canbos as $index => $cb)
                                            <tr>
                                                <td>{{ $cb->MaCB }}</td>
                                                <td>{{ $cb->HoTenCB }}</td>
                                                <td>{{ $cb->GioiTinh == 1 ? 'Nam' : 'Nữ' }}</td>
                                                <td>{{ $cb->Email }}</td>
                                                <td>{{ $cb->Sdt }}</td>
                                                <td>{{ $cb->hocvi->TenHocVi ?? 'N/A' }}</td>
                                                <td>{{ $cb->chucvu->TenChucVu ?? 'N/A' }}</td>
                                                <td>{{ $cb->donvi->TenDVHienTai ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('staff.show', $cb->MaCB) }}"
                                                        class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('staff.edit', $cb->MaCB) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('staff.destroy', $cb->MaCB) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-staff"
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
                            <div class="pagination mt-4 justify-content-center">
                                {{ $canbos->links() }}
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
            $('.delete-staff').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa cán bộ này?',
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