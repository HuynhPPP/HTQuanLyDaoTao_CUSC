@extends('layouts.new_app.master')

@section('title', 'Quản Lý Lịch Thi')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách lịch thi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh sách lịch thi</h4>
                    <div class="card-header-action">
                        <a href="{{ route('lichthi.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm lịch thi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Môn thi</th>
                                    <th>Lớp</th>
                                    <th>Ngày thi</th>
                                    <th>Giờ thi</th>
                                    <th>Phòng thi</th>
                                    <th>Hình thức</th>
                                    <th class="text-center">Lần thi</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lichThis as $index => $lichThi)
                                    <tr>
                                        <td>{{ $lichThi->monHoc->MaMH ?? 'N/A' }}</td>
                                        <td>{{ $lichThi->lopHoc->MaLop ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lichThi->NgayThi)->format('d-m-Y') }}</td>
                                        <td>{{ $lichThi->KhungGio }}</td>
                                        <td>{{ $lichThi->PhongThi }}</td>
                                        <td>{{ $lichThi->HinhThucThi }}</td>
                                        <td class="text-center">{{ $lichThi->LanThi ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('sinhvien.duthi.danh-sach', $lichThi->MaLichThi) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-list"></i> Danh sách dự thi
                                            </a>
                                            <a href="{{ route('lichthi.edit', $lichThi->MaLichThi) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('lichthi.destroy', $lichThi->MaLichThi) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa lịch thi này?')">
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
    </section>
@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-monhoc').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa môn học này?',
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
