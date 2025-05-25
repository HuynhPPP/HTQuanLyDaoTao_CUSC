@extends('layouts.new_app.master')
@section('title', 'Danh sách lịch thi')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Phân công cán bộ coi thi</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Môn học</th>
                                <th>Ngày thi</th>
                                <th>Khung giờ</th>
                                <th>Phòng thi</th>
                                <th>Loại thi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lichThis as $index => $lichThi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $lichThi->monHoc->TenMH }}</td>
                                    <td>{{ $lichThi->NgayThi }}</td>
                                    <td>{{ $lichThi->KhungGio }}</td>
                                    <td>{{ $lichThi->PhongThi }}</td>
                                    <td>{{ $lichThi->LoaiThi }}</td>
                                    <td>
                                        <a href="{{ route('phancong.create', $lichThi->MaLichThi) }}" 
                                           class="btn btn-primary btn-sm">
                                            Phân công cán bộ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có lịch thi nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-student').click(function(e) {
                e.preventDefault(); // Ngăn submit mặc định
                const form = $(this).closest('form'); // Tìm form cha gần nhất

                swal({
                    title: 'Bạn có chắc chắn muốn xóa sinh viên này?',
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