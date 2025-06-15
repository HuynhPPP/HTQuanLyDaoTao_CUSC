@extends('layouts.new_app.master')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Chọn chương trình đào tạo để Lập bảng thống kê</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('thongke.sinhvien.datmon', ['maChuongTrinh' => '__maChuongTrinh__']) }}" method="GET" id="formChonChuongTrinh">
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
                    <button type="submit" class="btn btn-primary" id="btnXemBangDiem" disabled>
                        <i class="fas fa-table"></i> Xem Bảng Điểm Tổng
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
    $('#maChuongTrinh').change(function() {
        var maChuongTrinh = $(this).val();
        $('#btnXemBangDiem').prop('disabled', !maChuongTrinh);

        // Cập nhật action form
        var newAction = "{{ route('thongke.sinhvien.datmon', ['maChuongTrinh' => '__maChuongTrinh__']) }}"
            .replace('__maChuongTrinh__', maChuongTrinh);
        
        $('#formChonChuongTrinh').attr('action', newAction);
    });
});
</script>
@endsection