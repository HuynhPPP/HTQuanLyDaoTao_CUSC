@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách tài khoản AD</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách tài khoản AD</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4></h4>
                            <a href="{{ route('ad-accounts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm tài khoản
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Tên đăng nhập</th>
                                            <th class="text-nowrap">Tên hiển thị</th>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Loại tài khoản</th>
                                            <th class="text-nowrap">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accounts as $account)
                                            <tr>
                                                <td class="text-nowrap">{{ $account->username }}</td>
                                                <td class="text-nowrap">{{ $account->display_name }}</td>
                                                <td class="text-wrap" style="max-width: 200px;">
                                                    <div class="text-truncate">{{ $account->email }}</div>
                                                </td>
                                                <td class="text-nowrap">{{ ucfirst($account->user_type) }}</td>
                                                <td class="text-nowrap">
                                                    <form action="{{ route('ad-accounts.destroy', $account->username) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-account" title="Xóa">
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
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.delete-account').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Bạn có chắc chắn muốn xóa tài khoản này?',
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