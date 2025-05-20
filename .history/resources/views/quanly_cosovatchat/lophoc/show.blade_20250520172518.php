@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Chi tiết lớp {{ $lophoc->TenLop }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('lophoc.index') }}">Danh sách lớp học</a></div>
            <div class="breadcrumb-item">Chi tiết lớp</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Danh sách sinh viên</h4>
                        <a href="{{ route('lophoc.add-student', $lophoc->MaLop) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sinh viên
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Mã sinh viên</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lophoc->danhSachSinhVien as $dssv)
                                        <tr>
                                            <td>{{ $dssv->sinhVien->MaSV }}</td>
                                            <td>{{ $dssv->sinhVien->HoTen }}</td>
                                            <td>{{ $dssv->sinhVien->Email }}</td>
                                            <td>{{ $dssv->sinhVien->Sdt }}</td>
                                            <td>
                                                <form action="" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete-sinhvien" onclick="return confirm('Bạn có chắc muốn xóa sinh viên này khỏi lớp?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có sinh viên trong lớp</td>
                                        </tr>
                                    @endforelse
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
        $(document).ready(function() {
            $('.delete-sinhvien').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                swal({
                    title: 'Bạn có chắc muốn xóa sinh viên này khỏi lớp ?',
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