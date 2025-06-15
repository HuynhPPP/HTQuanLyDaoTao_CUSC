@extends('layouts.new_app.master')

@section(section: 'main-content')
    <div class="section">
        <div class="section-header">
            <h1>Chọn lớp và môn học</h1>
        </div>

        <form action="{{ route('bang-diem-chi-tiet') }}" method="GET">
            <div class="form-group">
                <label for="maLop">Chọn lớp:</label>
                <select name="maLop" id="maLop" class="form-control select2" required>
                    @foreach ($dsLop as $lop)
                        <option value="{{ $lop->MaLop }}">{{ $lop->MaLop }} - {{ $lop->TenLop }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="tenMH">Chọn môn học:</label>
                <select name="tenMH" id="tenMH" class="form-control select2" required>
                    @foreach ($dsMon as $mon)
                        <option value="{{ $mon->TenMH }}">{{ $mon->TenMH }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Xem bảng điểm chi tiết</button>
        </form>
    </div>
@endsection
