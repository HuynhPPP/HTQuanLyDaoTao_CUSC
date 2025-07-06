@extends('layouts.new_app.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>Danh sách tài khoản sinh viên</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="card-title">Hãy chọn ít nhất một email để thực hiện gửi thông tin tài khoản</span>
                            <button id="guiTatCaEmail" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Gửi Email Cho Tất Cả
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="chonTatCa">
                                            </th>
                                            <th>Mã SV</th>
                                            <th>Họ Tên</th>
                                            <th>Tài Khoản đăng nhập</th>
                                            <th>Email</th>
                                            <th>Ngày Tạo</th>
                                            <th>Trạng Thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ldapAccounts as $account)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="chonTaiKhoan[]" value="{{ $account->id }}"
                                                        class="chon-tai-khoan" {{ $account->is_sent ? 'disabled' : '' }}>
                                                </td>
                                                <td>{{ $account->MaTaiKhoan }}</td>
                                                <td>{{ $account->full_name }}</td>
                                                <td>{{ $account->username }}</td>
                                                <td>{{ $account->email }}</td>
                                                <td>{{ $account->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    @if ($account->is_sent)
                                                        <span class="badge bg-success">Đã Gửi</span>
                                                    @else
                                                        <span class="badge bg-warning">Chưa Gửi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href=""
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-student"
                                                            title="Xóa">
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

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Đang xử lý...</span>
                    </div>
                    <p id="loadingText" class="mt-3">Đang gửi email. Vui lòng chờ...</p>
                    <div class="progress mt-3">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <p id="progressText" class="mt-2">0/0 email đã gửi</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Chọn tất cả tài khoản
            $('#chonTatCa').change(function() {
                $('.chon-tai-khoan:not(:disabled)').prop('checked', $(this).prop('checked'));
            });

            // Gửi email hàng loạt
            $('#guiTatCaEmail').click(function() {
                // Lấy các tài khoản được chọn
                var selectedAccounts = $('.chon-tai-khoan:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedAccounts.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn tài khoản',
                        text: 'Vui lòng chọn ít nhất một tài khoản để gửi email'
                    });
                    return;
                }

                // Hiển thị modal loading
                $('#loadingModal').modal('show');

                // Gửi yêu cầu ajax
                $.ajax({
                    url: "{{ route('ldap.account.send.bulk') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        accounts: selectedAccounts
                    },
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                $('#progressBar')
                                    .css('width', percentComplete * 100 + '%')
                                    .attr('aria-valuenow', percentComplete * 100);
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        $('#loadingModal').modal('hide');

                        // Cập nhật trạng thái các tài khoản đã gửi
                        response.sent.forEach(function(accountId) {
                            $(`input[value="${accountId}"]`).prop('disabled', true)
                                .closest('tr').find('.badge')
                                .removeClass('bg-warning')
                                .addClass('bg-success')
                                .text('Đã Gửi');
                        });

                        // Thông báo kết quả
                        Swal.fire({
                            icon: 'success',
                            title: 'Gửi Email Thành Công',
                            html: `Đã gửi email thành công cho ${response.sent.length}/${selectedAccounts.length} tài khoản`
                        });
                    },
                    error: function(xhr) {
                        $('#loadingModal').modal('hide');

                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: xhr.responseJSON.message ||
                                'Có lỗi xảy ra khi gửi email'
                        });
                    }
                });
            });
        });
    </script>
@endsection
