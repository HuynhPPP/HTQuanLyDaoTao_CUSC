@extends('layouts.new_app.master')

@section(section: 'main-content')
<div class="section">
    <h2>Chọn lớp và môn học</h2>

    <form action="{{ route('bangdiem.xem') }}" method="GET">
        <div class="form-group">
            <label for="maLop">Chọn lớp:</label>
            <select name="maLop" id="maLop" class="form-control" required>
                @foreach($dsLop as $lop)
                    <option value="{{ $lop->MaLop }}">{{ $lop->MaLop }} - {{ $lop->TenLop }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="tenMH">Chọn môn học:</label>
            <select name="tenMH" id="tenMH" class="form-control" required>
                @foreach($dsMon as $mon)
                    <option value="{{ $mon->TenMH }}">{{ $mon->TenMH }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Xem bảng điểm</button>
    </form>
</div>
@endsection
