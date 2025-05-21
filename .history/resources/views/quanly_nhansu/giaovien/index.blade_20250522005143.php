@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Danh sách giáo viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Danh sách giáo viên</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Danh sách giáo viên</h4>
                            <div class="card-header-action">
                                <a href="{{ route('giaovien.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm giáo viên
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã GV</th>
                                            <th>Họ và tên</th>
                                            <th>Email</th>
                                            <th>Vai trò giảng viên</th>
                                            <th>Chuyên ngành</th>
                                            <th>Học vị</th>
                                            <th>Đơn vị</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giaoviens as $index => $gv)
                                            <tr>
                                                <td>{{ $gv->MaGV }}</td>
                                                <td>{{ $gv->HoTenGV }}</td>
                                                <td>{{ $gv->Email }}</td>
                                                <td>
                                                    <span
                                                        class="badge 
                                                        {{ $gv->LoaiGV == 'CoHuu' ? 'badge-primary' : 'badge-warning' }}">
                                                        {{ $gv->LoaiGV == 'CoHuu' ? 'Cơ hữu' : 'Mời giảng' }}
                                                    </span>
                                                </td>
                                                <td>{{ $gv->ChuyenNganh }}</td>
                                                <td>{{ optional($gv->hocvi)->TenHocVi ?? 'N/A' }}</td>
                                                <td>{{ optional($gv->donvi)->TenDVHienTai ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('giaovien.show', $gv->MaGV) }}"
                                                        class="btn btn-info btn-sm" data-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- <a href="{{ route('giaovien.edit', $gv->MaGV) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a> --}}
                                                    <form action="{{ route('giaovien.destroy', $gv->MaGV) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                                            data-toggle="tooltip" title="Xóa">
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
    </div>
@endsection

@section('custom-js')
    <script>
        // Xử lý xóa giáo viên
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);

            swal({
                title: 'Bạn có chắc chắn muốn xóa giáo viên này?',
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
    </script>
@endsection
