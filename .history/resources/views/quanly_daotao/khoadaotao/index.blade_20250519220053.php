@extends('layouts.new_app.master')

@section('title', 'Danh khoá đào tạo')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh khoá đào tạo</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang chủ</a></div>
                <div class="breadcrumb-item">Danh khoá đào tạo</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Danh khoá đào tạo</h4>
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
                                    <th>Tên khóa đào tạo</th>
                                    <th>Thời gian đào tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($khoadaotao as $kdt)
                                    <tr>
                                        <td>{{ $kdt->TenKhoaDaoTao }}</td>
                                        <td>{{ $kdt->ThoiGianDaoTao }}</td>
                                        <td>
                                            <a href="{{ route('khoadaotao.edit', $kdt->TenKhoaDaoTao) }}"
                                                class="btn btn-warning">Sửa</a>
                                            <form action="{{ route('khoadaotao.destroy', $kdt->TenKhoaDaoTao) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-khoadaotao">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-khoadaotao').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn khoá đào tạo này?',
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
