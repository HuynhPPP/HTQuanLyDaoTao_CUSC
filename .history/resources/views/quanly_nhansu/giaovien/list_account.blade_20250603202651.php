@extends('layouts.new_app.master')

@section('css')
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách tài khoản giáo viên</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('about') }}">Trang Chủ</a></div>
                <div class="breadcrumb-item">Danh sách tài khoản giáo viên</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Gửi thông tin tài khoản hệ thống đến giáo viên qua email cá nhân</h4>
                            <div class="card-header-action">
                                <button id="sendAllBtn" class="btn btn-primary">Gửi Tất Cả</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th>Mã GV</th>
                                            <th>Họ Tên</th>
                                            <th>Tài Khoản đăng nhập</th>
                                            <th>Email</th>
                                            <th>Ngày Tạo</th>
                                            <th>Trạng Thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ldapAccounts as $account)
                                            <tr>
                                                <td>
                                                    @if (!$account->is_sent)
                                                        <input type="checkbox" name="selected_accounts[]" value="{{ $account->id }}" class="account-checkbox">
                                                    @endif
                                                </td>
                                                <td>{{ $account->MaTaiKhoan }}</td>
                                                <td>{{ $account->full_name }}</td>
                                                <td>{{ $account->username }}</td>
                                                <td>{{ $account->email }}</td>
                                                <td>{{ $account->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    @if (!$account->is_sent)
                                                        <button class="btn btn-primary btn-sm send-single-btn" data-id="{{ $account->id }}">
                                                            Gửi Email
                                                        </button>
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

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Checkbox toàn bộ
        $('#checkAll').change(function() {
            $('.account-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Hàm hiển thị loading
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex');
        }

        // Hàm ẩn loading
        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        // Gửi email từng người
        $('.send-single-btn').click(function(e) {
            e.preventDefault();
            const accountId = $(this).data('id');
            sendEmails([accountId]);
        });

        // Gửi email tất cả
        $('#sendAllBtn').click(function() {
            const selectedAccounts = $('input[name="selected_accounts[]"]:checked')
                .map(function() {
                    return $(this).val();
                }).get();

            if (selectedAccounts.length === 0) {
                alert('Vui lòng chọn ít nhất một tài khoản');
                return;
            }

            sendEmails(selectedAccounts);
        });

        // Hàm xử lý gửi email
        function sendEmails(accountIds) {
            showLoading();

            $.ajax({
                url: "{{ route('giaovien.ldap.account.send.multiple') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    account_ids: accountIds
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Gửi Email Thành Công',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã có lỗi xảy ra khi gửi email'
                    });
                }
            });
        }
    });
</script>
@endsection
