@extends('layouts.new_app.master')

@section('main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chọn chương trình và lớp học để lập bảng thống kê</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="#" method="GET" id="formChonChuongTrinhLop">
                    <div class="form-group">
                        <label for="maChuongTrinh">Chọn chương trình đào tạo:</label>
                        <select name="maChuongTrinh" id="maChuongTrinh" class="form-control select2" required>
                            <option value="">-- Chọn chương trình --</option>
                            @foreach ($dsChuongTrinh as $chuongTrinh)
                                <option value="{{ $chuongTrinh->MaChuongTrinh }}">
                                    {{ $chuongTrinh->MaChuongTrinh }} - {{ $chuongTrinh->TenChuongTrinh }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="maLop">Chọn lớp học:</label>
                        <select name="maLop" id="maLop" class="form-control select2" required>
                            <option value="">-- Chọn lớp học --</option>
                            @foreach ($dsLop as $lop)
                                <option value="{{ $lop->MaLop }}">{{ $lop->MaLop }} - {{ $lop->TenLop }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="btnXemBangDiem" disabled>
                            <i class="fas fa-table"></i> Xem bảng thống kê
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
            function updateFormAction() {
                const maCT = $('#maChuongTrinh').val();
                const maLop = $('#maLop').val();
                const isValid = maCT && maLop;

                $('#btnXemBangDiem').prop('disabled', !isValid);

                if (isValid) {
                    const newAction =
                        "{{ route('thong-ke.diemthi.tongket.hocluc', ['MaLop' => '__lop__', 'MaChuongTrinh' => '__ct__']) }}"
                        .replace('__lop__', maLop)
                        .replace('__ct__', maCT);
                    $('#formChonChuongTrinhLop').attr('action', newAction);
                }
            }

            $('#maChuongTrinh, #maLop').change(updateFormAction);
        });
    </script>
@endsection
