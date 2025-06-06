<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="{{ route('about') }}" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                        class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        @if (session('user'))
            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="{{ asset('images/avatar-1.png') }}" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">{{ session('displayname') }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @if (session('role') == 'teacher')
                        <a href="{{ route('giaovien.profile') }}" class="dropdown-item has-icon">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                        <a href="#" class="dropdown-item has-icon" data-toggle="modal"
                            data-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Đổi mật khẩu
                        </a>
                    @endif
                    <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </li>
        @else
            <li class="dropdown"><a href="{{ route('login') }}" class="nav-link nav-link-lg nav-link-user">
                    <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">Đăng nhập</div>
                </a>
            </li>
        @endif

    </ul>
</nav>

@include('layouts.new_app.change_password')

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Reset lỗi khi mở modal
            $('#changePasswordModal').on('show.bs.modal', function () {
                resetErrors();
            });

            // Hàm reset các thông báo lỗi
            function resetErrors() {
                $('#current_password_error').text('');
                $('#new_password_error').text('');
                $('#confirm_password_error').text('');
                $('#general_error').text('');
            }

            // Hàm hiển thị lỗi
            function displayErrors(errors) {
                if (errors.current_password) {
                    $('#current_password_error').text(errors.current_password[0]);
                }
                if (errors.new_password) {
                    $('#new_password_error').text(errors.new_password[0]);
                }
                if (errors.confirm_password) {
                    $('#confirm_password_error').text(errors.confirm_password[0]);
                }
            }

            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault();
                
                // Reset lỗi trước khi validate
                resetErrors();

                var currentPassword = $('#current_password').val();
                var newPassword = $('#new_password').val();
                var confirmPassword = $('#confirm_password').val();

                // Kiểm tra độ dài mật khẩu
                if (newPassword.length < 8) {
                    $('#new_password_error').text('Mật khẩu mới phải có ít nhất 8 ký tự');
                    return false;
                }

                // Kiểm tra mật khẩu mới và xác nhận
                if (newPassword !== confirmPassword) {
                    $('#confirm_password_error').text('Xác nhận mật khẩu không khớp');
                    return false;
                }

                // Kiểm tra mật khẩu hiện tại không được trùng mật khẩu mới
                if (currentPassword === newPassword) {
                    $('#new_password_error').text('Mật khẩu mới không được trùng mật khẩu hiện tại');
                    return false;
                }

                // Gửi form qua Ajax
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: 'Đổi mật khẩu thành công',
                                position: 'topRight'
                            });
                            $('#changePasswordModal').modal('hide');
                        } else {
                            $('#general_error').text(response.message || 'Đổi mật khẩu thất bại');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Lỗi validation
                            var errors = xhr.responseJSON.errors;
                            displayErrors(errors);
                        } else {
                            // Lỗi khác
                            var errorMessage = xhr.responseJSON.message || 'Có lỗi xảy ra';
                            $('#general_error').text(errorMessage);
                        }
                    }
                });
            });
        });
    </script>
@endsection
