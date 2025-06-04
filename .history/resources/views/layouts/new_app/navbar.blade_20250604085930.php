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
                        <a href="#" class="dropdown-item has-icon" data-toggle="modal" data-target="#changePasswordModal">
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
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            var currentPassword = $('#current_password').val();
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#confirm_password').val();

            // Kiểm tra độ dài mật khẩu
            if (newPassword.length < 8) {
                iziToast.error({
                    message: 'Mật khẩu mới phải có ít nhất 8 ký tự',
                    position: 'topRight'
                });
                return false;
            }

            // Kiểm tra mật khẩu mới và xác nhận
            if (newPassword !== confirmPassword) {
                iziToast.error({
                    message: 'Mật khẩu mới và xác nhận mật khẩu không khớp',
                    position: 'topRight'
                });
                return false;
            }

            // Kiểm tra mật khẩu hiện tại không được trùng mật khẩu mới
            if (currentPassword === newPassword) {
                iziToast.error({
                    message: 'Mật khẩu mới không được trùng mật khẩu hiện tại',
                    position: 'topRight'
                });
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
                        iziToast.error({
                            message: response.message || 'Đổi mật khẩu thất bại',
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Có lỗi xảy ra';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    iziToast.error({
                        message: errorMessage,
                        position: 'topRight'
                    });
                }
            });
        });
    });
</script>
@endsection
