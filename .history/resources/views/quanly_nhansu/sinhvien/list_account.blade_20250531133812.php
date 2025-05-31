@extends('layouts.new_app.master')

@section('main-content')
<div class="container">
    <h2>Danh Sách Tài Khoản LDAP Mới</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Tài Khoản</th>
                <th>Email</th>
                <th>Ngày Tạo</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ldapAccounts as $account)
            <tr>
                <td>{{ $account->MaSV }}</td>
                <td>{{ $account->full_name }}</td>
                <td>{{ $account->username }}</td>
                <td>{{ $account->email }}</td>
                <td>{{ $account->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if(!$account->is_sent)
                    <form action="{{ route('ldap.account.send', $account->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Gửi Email</button>
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
@endsection