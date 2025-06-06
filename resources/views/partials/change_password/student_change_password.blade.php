<!-- Modal Đổi Mật Khẩu -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changePasswordForm" method="POST" action="{{ route('student.change.password') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                        <small class="text-danger" id="current_password_error"></small>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <small class="text-danger" id="new_password_error"></small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        <small class="text-danger" id="confirm_password_error"></small>
                    </div>
                    <div class="form-group">
                        <small class="text-danger" id="general_error"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
