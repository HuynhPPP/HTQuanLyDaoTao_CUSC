@extends('layouts.new_app.master')
@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>Quản lý phòng học</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4></h4>
                        <a href="{{ route('phonghoc.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mới phòng học
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Tên phòng</th>
                                        <th>Loại phòng</th>
                                        <th>Sức chứa</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($phonghocs as $phong)
                                    <tr>
                                        <td>{{ $phong->TenPhong }}</td>
                                        <td>{{ $phong->LoaiPhong }}</td>
                                        <td>{{ $phong->SucChua }}</td>
                                        <td>
                                            {{-- <a href="{{ route('phonghoc.show', $phong->TenPhong) }}" class="btn btn-info btn-sm">Xem</a> --}}
                                            <a href="{{ route('phonghoc.edit', $phong->TenPhong) }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <form action="{{ route('phonghoc.destroy', $phong->TenPhong) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-phonghoc">Xóa</button>
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
            $('.delete-phonghoc').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa phòng học này?',
                    text: 'Nếu phòng học đang được gán cho lớp, bạn cần xóa các gán phòng trước khi xóa phòng học này. Dữ liệu đã xóa sẽ không thể khôi phục!',
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