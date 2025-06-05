@extends('layouts.new_app.master')

@section('title', 'Quản Lý Môn Học')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh Sách Môn Học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Môn Học</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh Sách Môn Học</h4>
                    <div class="card-header-action">
                        <a href="{{ route('monhoc.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã Môn Học</th>
                                    <th>Tên Môn Học</th>
                                    <th>Giảng viên phụ trách</th>
                                    <th>Giờ Gốc</th>
                                    <th>Giờ Triển Khai</th>
                                    <th>Loại Tiết Học</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monhocs as $monhoc)
                                    <tr>
                                        <td>{{ $monhoc->MaMH }}</td>
                                        <td>{{ $monhoc->TenMH }}</td>
                                        <td>
                                            @if ($monhoc->giangViens->count() > 0)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-primary mr-2">
                                                        {{ $monhoc->giangViens->count() }}
                                                        <i class="fas fa-chalkboard-teacher ml-1"></i>
                                                    </span>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-info dropdown-toggle"
                                                            type="button" id="teacherDropdown{{ $monhoc->TenMH }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            Xem chi tiết
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="teacherDropdown{{ $monhoc->TenMH }}">
                                                            @foreach ($monhoc->giangViens as $gv)
                                                                <div class="dropdown-item-text">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <span>
                                                                            <strong>{{ $gv->MaGV }}</strong> -
                                                                            {{ $gv->HoTenGV }}
                                                                        </span>
                                                                        <div class="btn-group btn-group-sm ml-2"
                                                                            role="group">
                                                                            <a href="{{ route('monhoc.edit-teacher', ['tenMH' => $monhoc->TenMH, 'maGV' => $gv->MaGV]) }}"
                                                                                class="btn btn-warning"
                                                                                data-toggle="tooltip"
                                                                                title="Chỉnh sửa thông tin">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <form
                                                                                action="{{ route('monhoc.remove-teacher', ['tenMH' => $monhoc->TenMH, 'maGV' => $gv->MaGV]) }}"
                                                                                method="POST"
                                                                                class="d-inline remove-teacher-form">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger remove-teacher-btn"
                                                                                    data-toggle="tooltip"
                                                                                    title="Xoá giảng viên">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('monhoc.add-teacher', $monhoc->TenMH) }}"
                                                    class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-user-plus"></i> Phân công giảng viên
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $monhoc->GioGoc }}</td>
                                        <td>{{ $monhoc->GioTrienKhai }}</td>
                                        <td>
                                            @php
                                                $loaiTiet = [];
                                                if ($monhoc->TietLT) {
                                                    $loaiTiet[] = 'Lý thuyết';
                                                }
                                                if ($monhoc->TietTH) {
                                                    $loaiTiet[] = 'Thực hành';
                                                }
                                                if ($monhoc->TietLTvaTH) {
                                                    $loaiTiet[] = 'LT & TH';
                                                }
                                            @endphp

                                            @if (!empty($loaiTiet))
                                                @foreach ($loaiTiet as $index => $loai)
                                                    <span
                                                        class="badge 
                                                        {{ $index == 0 ? 'badge-primary' : ($index == 1 ? 'badge-success' : 'badge-warning') }} 
                                                        mr-1">
                                                        {{ $loai }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">Chưa xác định</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('monhoc.edit', $monhoc->TenMH) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('monhoc.destroy', $monhoc->TenMH) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-monhoc">
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
    <script>
        $(document).ready(function() {
            $('.remove-teacher-btn').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xoá giảng viên khỏi môn học này?',
                    text: 'Thao tác này không thể hoàn tác!',
                    icon: 'warning',
                    buttons: ['Hủy', 'Xoá'],
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
