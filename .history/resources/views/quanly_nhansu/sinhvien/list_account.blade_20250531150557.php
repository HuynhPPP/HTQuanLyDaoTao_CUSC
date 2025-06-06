@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách tài khoản sinh viên</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                    <div class="breadcrumb-item">Danh sách tài khoản sinh viên</div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>Mã SV</th>
                                                <th>Họ Tên</th>
                                                <th>Tài Khoản đăng nhập</th>
                                                <th>Email</th>
                                                <th>Ngày Tạo</th>
                                                <th>Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ldapAccounts as $account)
                                                <tr>
                                                    <td>{{ $account->MaSV }}</td>
                                                    <td>{{ $account->full_name }}</td>
                                                    <td>{{ $account->username }}</td>
                                                    <td>{{ $account->email }}</td>
                                                    <td>{{ $account->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if (!$account->is_sent)
                                                            <form action="{{ route('ldap.account.send', $account->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm">Gửi
                                                                    Email</button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-success">Đã Gửi</span>
                                                        @endif
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
