
<div class="modal fade" id="modal-addTime" tabindex="-1" role="dialog" aria-labelledby="absenceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="absenceModalLabel">Thêm Ngày Nghỉ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="absenceForm" method="POST" action="{{ route('saveholiday', ['TenTKB' => $schedule->TenTKB]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="TenNgayNghi" class="form-label">Tên ngày nghỉ:</label>
                        <textarea class="form-control" id="TenNgayNghi" name="TenNgayNghi" rows="1" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="NgayBDNghi" class="form-label">Ngày bắt đầu nghỉ:</label>
                        <input type="date" class="form-control" id="NgayBDNghi" name="NgayBDNghi" required>
                    </div>
                    <div class="form-group">
                        <label for="NgayKT" class="form-label">Ngày kết thúc:</label>
                        <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="saveAbsenceButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>