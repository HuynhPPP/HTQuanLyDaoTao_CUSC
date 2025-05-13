"use strict";

$("#modal-1").fireModal({body: 'Modal body text goes here.'});



// {{-- <div class="modal-dialog" role="document">
//     <div class="modal-content">
//         <div class="modal-header">
//             <h5 class="modal-title" id="absenceModalLabel">Thêm Ngày Nghỉ</h5>
//             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
//                 <span aria-hidden="true">&times;</span>
//             </button>
//         </div>
//         <div class="modal-body">
//             <form id="absenceForm" method="POST"
//                 action="{{ route('saveholiday', ['TenTKB' => $schedule->TenTKB]) }}">
//                 @csrf
//                 <div class="form-group">
//                     <label for="TenNgayNghi" class="form-label">Tên ngày nghỉ:</label>
//                     <textarea class="form-control" id="TenNgayNghi" name="TenNgayNghi" rows="1" required></textarea>
//                 </div>
//                 <div class="form-group">
//                     <label for="NgayBDNghi" class="form-label">Ngày bắt đầu nghỉ:</label>
//                     <input type="date" class="form-control" id="NgayBDNghi" name="NgayBDNghi" required>
//                 </div>
//                 <div class="form-group">
//                     <label for="NgayKT" class="form-label">Ngày kết thúc:</label>
//                     <input type="date" class="form-control" id="NgayKT" name="NgayKT" required>
//                 </div>
//                 <div class="modal-footer">
//                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
//                     <button type="submit" class="btn btn-primary" id="saveAbsenceButton">Lưu</button>
//                 </div>
//             </form>
//         </div>
//     </div>
// </div> --}}
{/* {{-- <!-- Absence Modal -->
<div class="modal fade" id="absenceModal" tabindex="-1" role="dialog" aria-labelledby="absenceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="absenceModalLabel">Thêm Ngày Nghỉ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="absenceForm" method="POST"
                    action="{{ route('saveholiday', ['TenTKB' => $schedule->TenTKB]) }}">
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

<!-- Time Slot Modal -->
<div class="modal fade" id="timeSlotModal" tabindex="-1" role="dialog" aria-labelledby="timeSlotModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timeSlotModalLabel">Thêm Khung Giờ Học</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="timeSlotForm" method="POST"
                    action="{{ route('saveTimeSlot', ['TenTKB' => $schedule->TenTKB]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="khunggio" class="form-label">Tên khung giờ</label>
                        <select id="khunggio" class="form-control @error('khunggio') is-invalid @enderror"
                            name="khunggio">
                            <option value="">----- Tên khung giờ -----</option>
                            @foreach ($khunggio as $kg)
                                <option value="{{ $kg->TenKhungGio }}">{{ $kg->TenKhungGio }}</option>
                            @endforeach
                        </select>
                        @error('khunggio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="saveTimeSlotButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Self Study Modal -->
<div class="modal fade" id="SelfStudyModal" tabindex="-1" role="dialog" aria-labelledby="SelfStudyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SelfStudyModalLabel">Thêm ngày tự học</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="SelfStudyForm" method="POST"
                    action="{{ route('saveSelfStudy', ['TenTKB' => $schedule->TenTKB]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="ngaytuhoc" class="form-label">Tên Ngày Tự Học</label>
                        <select id="ngaytuhoc" class="form-control @error('ngaytuhoc') is-invalid @enderror"
                            name="ngaytuhoc">
                            <option value="">----- Tên Ngày Tự Học -----</option>
                            <option value="Self Study">Self Study</option>
                            <option value="Team works">Team Works</option>
                        </select>
                        @error('ngaytuhoc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="NgayBDTuHoc" class="form-label">Ngày bắt đầu tự học:</label>
                        <input type="date" class="form-control" id="NgayBDTuHoc" name="NgayBDTuHoc" required>
                    </div>
                    <div class="form-group">
                        <label for="NgayKTTuHoc" class="form-label">Ngày kết thúc tự học:</label>
                        <input type="date" class="form-control" id="NgayKTTuHoc" name="NgayKTTuHoc" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="saveSelfStudyButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit TKB Modal -->
<div class="modal fade" id="EditTKBModal" tabindex="-1" role="dialog" aria-labelledby="EditTKBModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditTKBModalLabel">Chỉnh sửa thời gian khai giảng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="EditTKBForm" method="POST"
                    action="{{ route('EditTKB', ['TenTKB' => $schedule->TenTKB]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="NgayHoc" class="form-label">Ngày bắt đầu học</label>
                        <input type="date" class="form-control @error('NgayHoc') is-invalid @enderror"
                            id="NgayHoc" name="NgayHoc">
                        @error('NgayHoc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="saveEditTKBButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}} */}
