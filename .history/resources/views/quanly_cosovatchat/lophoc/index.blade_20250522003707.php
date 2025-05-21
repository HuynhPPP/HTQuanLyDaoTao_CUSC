@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Quản lý lớp học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Quản lý lớp học</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('lophoc.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm mới lớp học
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Mã lớp</th>
                                            <th>Tên lớp</th>
                                            <th>Giảng viên phụ trách</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Mã chương trình</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lophocs as $lop)
                                            <tr>
                                                <td>{{ $lop->MaLop }}</td>
                                                <td>{{ $lop->TenLop }}</td>
                                                <td>
                                                    @if($lop->giangvien)
                                                        {{ $lop->giangvien->HoTenGV }}
                                                    @else
                                                        <a href="{{ route('lophoc.add-teacher', $lop->MaLop) }}" 
                                                           class="btn btn-info btn-sm">
                                                            <i class="fas fa-user-plus"></i> Phân công giảng viên
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $lop->NgayBatDau }}</td>
                                                <td>{{ $lop->MaChuongTrinh }}</td>
                                                <td>
                                                    <a href="{{ route('lophoc.show', $lop->MaLop) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('lophoc.edit', $lop->MaLop) }}"
                                                        class="btn btn-warning btn-sm" title="Sửa"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('lophoc.destroy', $lop->MaLop) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-lophoc"
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
            $('.delete-lophoc').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                swal({
                    title: 'Bạn có chắc chắn muốn xóa lớp học này?',
                    text: 'Nếu lớp học đang được gán phòng, bạn cần xóa các gán phòng trước khi xóa lớp học này. Dữ liệu đã xóa sẽ không thể khôi phục!',
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
