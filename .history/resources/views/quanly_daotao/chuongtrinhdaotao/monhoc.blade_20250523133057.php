@extends('layouts.new_app.master')

@section('title', 'Quản Lý Môn Học Chương Trình Đào Tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Thêm môn học vào chương trình</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('chuongtrinh.index') }}">Chương trình đào tạo</a></div>
                <div class="breadcrumb-item">Thêm môn học vào chương trình</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $chuongTrinh->TenChuongTrinh }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('chuongtrinh.monhoc.store', $chuongTrinh->MaChuongTrinh) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="TenMH">Chọn môn học <span class="text-danger">*</span></label>
                                    <select class="form-control @error('TenMH') is-invalid @enderror select2" id="TenMH"
                                        name="TenMH[]" multiple="">
                                        <option value="">-- Chọn môn học --</option>
                                        @foreach ($monHocList as $monHoc)
                                            <option value="{{ $monHoc->TenMH }}"
                                                {{ old('TenMH') == $monHoc->TenMH ? 'selected' : '' }}>
                                                {{ $monHoc->TenMH }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('TenMH')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Thêm môn học</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Danh sách môn học trong chương trình</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên môn học</th>
                                    <th>Loại tiết học</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monHocDaGan as $index => $monHoc)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $monHoc->TenMH }}</td>
                                        <td>
                                            @if($monHoc->monHoc->TietLT) Lý thuyết @endif
                                            @if($monHoc->TietTH) Thực hành @endif
                                            @if($monHoc->TietLTvaTH) Lý thuyết và Thực hành @endif
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('chuongtrinh.monhoc.destroy', [$chuongTrinh->MaChuongTrinh, $monHoc->TenMH]) }}"
                                                method="POST" class="d-inline">
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
                    text: 'Môn học sẽ bị xóa khỏi chương trình đào tạo!',
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
