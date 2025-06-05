@extends('layouts.new_app.master')

@section('title', 'Quản Lý Môn Học')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách môn học</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Môn học</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-action">
                        <a href="{{ route('monhoc.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mới môn học
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Mã môn học</th>
                                    <th>Tên môn học</th>
                                    <th>Giảng viên phụ trách</th>
                                    <th>Giờ gốc</th>
                                    <th>Giờ triển khai</th>
                                    <th>Loại tiết học</th>
                                    <th>Hành động</th>
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
                                                            type="button" id="teacherDropdown{{ $monhoc->MaMH }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            Giảng Viên
                                                        </button>

                                                        <div class="dropdown-menu"
                                                            aria-labelledby="teacherDropdown{{ $monhoc->MaMH }}">
                                                            @foreach ($monhoc->giangViens as $gv)
                                                                <div class="dropdown-item">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <strong>{{ $gv->HoTenGV }}</strong>
                                                                            @if ($gv->NgayBatDau || $gv->NgayKetThuc)
                                                                                <small class="d-block text-muted">
                                                                                    {{ $gv->NgayBatDau ? 'Từ: ' . $gv->NgayBatDau : '' }}
                                                                                    {{ $gv->NgayKetThuc ? 'Đến: ' . $gv->NgayKetThuc : '' }}
                                                                                </small>
                                                                            @endif
                                                                        </div>

                                                                        <div class="btn-group btn-group-sm ml-2"
                                                                            role="group">
                                                                            <a href="{{ route('monhoc.edit-teacher', ['MaMH' => $monhoc->MaMH, 'maGV' => $gv->MaGV]) }}"
                                                                                class="btn btn-warning"
                                                                                data-toggle="tooltip"
                                                                                title="Chỉnh sửa thông tin">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <a href="{{ route('monhoc.remove-teacher', ['MaMH' => $monhoc->MaMH, 'maGV' => $gv->MaGV]) }}"
                                                                                class="btn btn-danger remove-teacher-btn"
                                                                                data-toggle="tooltip"
                                                                                title="Chỉnh sửa thông tin">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-teacher-btn"
                                                                                data-maMH="{{ $monhoc->MaMH }}"
                                                                                data-maGV="{{ $gv->MaGV }}"
                                                                                data-toggle="tooltip"
                                                                                title="Xoá giảng viên">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            <div class="dropdown-divider"></div>
                                                            <a href="{{ route('monhoc.add-teacher', $monhoc->MaMH) }}"
                                                                class="dropdown-item text-primary">
                                                                <i class="fas fa-plus-circle mr-2"></i>Phân công giảng dạy
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('monhoc.add-teacher', $monhoc->MaMH) }}"
                                                    class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-user-plus mr-1"></i>Phân công giảng dạy
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
                                                    <span>
                                                        {{ $loai }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">Chưa xác định</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('monhoc.edit', $monhoc->MaMH) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('monhoc.destroy', $monhoc->MaMH) }}" method="POST"
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
        // Kích hoạt tooltip
        $('[data-toggle="tooltip"]').tooltip();
    </script>
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
                    title: 'Bạn có chắc chắn muốn huỷ phân công giảng viên khỏi môn học này?',
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
