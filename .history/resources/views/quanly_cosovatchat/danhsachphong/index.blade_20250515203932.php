@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Gán phòng cho lớp</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('danhsachphong.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm gán phòng
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã lớp</th>
                                            <th>Tên phòng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($danhsachphongs as $item)
                                            <tr>
                                                <td>{{ $item->MaLop }}</td>
                                                <td>{{ $item->TenPhong }}</td>
                                                <td>
                                                    {{-- <a href="{{ route('danhsachphong.show', $item->MaLop) }}" class="btn btn-info btn-sm">Xem</a> --}}
                                                    <a href="{{ route('danhsachphong.edit', $item->MaLop) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa"><i
                                                        class="fas fa-edit"></i></a>
                                                    <form action="{{ route('danhsachphong.destroy', $item->MaLop) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm delete-danhsachphong"
                                                            title="Xóa"><i class="fas fa-trash"></i></button>
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
            $('.delete-danhsachphong').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                swal({
                    title: 'Bạn có chắc chắn muốn xóa gán phòng này?',
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
