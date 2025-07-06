<div class="modal fade" tabindex="-1" role="dialog" id="changePasswordModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changePasswordForm" action="{{ route('admin.change.password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mật khẩu hiện tại</label> <span class="text-danger">*</span>
                        <input type="password" class="form-control" name="current_password" required>
                        <small id="current_password_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới</label> <span class="text-danger">*</span>
                        <input type="password" class="form-control" name="new_password" required>
                        <small id="new_password_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Xác nhận mật khẩu mới</label> <span class="text-danger">*</span>
                        <input type="password" class="form-control" name="confirm_password" required>
                        <small id="confirm_password_error" class="text-danger"></small>
                    </div>
                    <small id="general_error" class="text-danger"></small>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
