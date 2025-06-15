@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chọn Lớp và Môn Học Để Lập Bảng Điểm</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('bang-diem-chi-tiet', ['maLop' => '__maLop__', 'maMH' => '__maMH__']) }}"
                    method="GET" id="formChonLopMon">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maLop">Chọn Lớp:</label>
                                <select name="maLop" id="maLop" class="form-control select2" required>
                                    <option value="">-- Chọn Lớp --</option>
                                    @foreach ($dsLop as $lop)
                                        <option value="{{ $lop->MaLop }}">
                                            {{ $lop->MaLop }} - {{ $lop->TenLop }}
                                            ({{ $lop->MaChuongTrinh }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maMH">Chọn Môn Học:</label>
                                <select name="maMH" id="maMH" class="form-control select2" required disabled>
                                    <option value="">-- Chọn Môn Học --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="btnXemBangDiem" disabled>
                            <i class="fas fa-table"></i> Xem Bảng Điểm Chi Tiết
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            // Khi chọn lớp
            $('#maLop').change(function() {
                var maLop = $(this).val();

                // Disable nút xem và select môn học
                $('#maMH').prop('disabled', true).html('<option value="">-- Chọn Môn Học --</option>');
                $('#btnXemBangDiem').prop('disabled', true);

                if (maLop) {
                    // Gọi AJAX để lấy danh sách môn học theo lớp
                    $.ajax({
                        url: '{{ route('get-mon-hoc-theo-lop') }}',
                        method: 'GET',
                        data: {
                            maLop: maLop
                        },
                        success: function(response) {
                            // Điền danh sách môn học
                            var selectMH = $('#maMH');
                            selectMH.prop('disabled', false);
                            selectMH.html('<option value="">-- Chọn Môn Học --</option>');

                            response.forEach(function(mon) {
                                selectMH.append(
                                    `<option value="${mon.MaMH}">${mon.TenMH} (${mon.MaMH})</option>`
                                );
                            });
                        },
                        error: function() {
                            alert('Lỗi khi tải danh sách môn học');
                        }
                    });
                }
            });

            // Khi chọn môn học
            $('#maMH').change(function() {
                var maMH = $(this).val();
                $('#btnXemBangDiem').prop('disabled', !(maMH && $('#maLop').val()));

                // Cập nhật action form
                var maLop = $('#maLop').val();
                var newAction =
                    "{{ route('bang-diem-chi-tiet', ['maLop' => '__maLop__', 'maMH' => '__maMH__']) }}"
                    .replace('__maLop__', maLop)
                    .replace('__maMH__', maMH);

                $('#formChonLopMon').attr('action', newAction);
            });
        });
    </script>
@endsection
