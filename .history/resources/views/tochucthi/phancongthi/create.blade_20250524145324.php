@extends('layouts.new_app.master')
@section('title', 'Phân công cán bộ thi')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Phân công cán bộ thi</h1>
    </div>

    <div class="section-body">

        <form method="POST" action="{{ route('phancong.store') }}">
            @csrf
            <div class="form-group">
                <label for="MaLichThi">Chọn lịch thi</label>
                <select name="MaLichThi" class="form-control" required>
                    <option value="">-- Chọn lịch thi --</option>
                    @foreach($lichThis as $lich)
                        <option value="{{ $lich->id }}">
                            {{ $lich->monHoc->TenMH ?? 'N/A' }} - {{ $lich->NgayThi }} ({{ $lich->MaLop }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="MaCB">Chọn cán bộ</label>
                <select name="MaCB" class="form-control select2" multiple="">
                    <option value="">-- Chọn cán bộ --</option>
                    @foreach($canBos as $cb)
                        <option value="{{ $cb->MaCB }}">{{ $cb->HoTenCB }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="VaiTro">Vai trò</label>
                <select name="VaiTro" class="form-control" required>
                    <option value="Cán bộ coi thi">Cán bộ coi thi</option>
                    <option value="Giám sát">Giám sát</option>
                    <option value="Chấm thi">Chấm thi</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Phân công</button>
        </form>
    </div>
</div>
@endsection
