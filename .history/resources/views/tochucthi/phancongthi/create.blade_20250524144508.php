@extends('layouts.new_app.master')
@section('title', 'Phân Công Thi')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Phân công cán bộ coi thi</h1>
    </div>
    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('phancong.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="MaLichThi">Chọn lịch thi</label>
                <select name="MaLichThi" class="form-control" required>
                    @foreach($lichThis as $lichThi)
                        <option value="{{ $lichThi->id }}"> {{ $lichThi->monHoc->TenMH }} - {{ $lichThi->NgayThi }} </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="MaCB">Chọn cán bộ</label>
                <select name="MaCB" class="form-control" required>
                    @foreach($canBos as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->HoTen }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="VaiTro">Vai trò</label>
                <select name="VaiTro" class="form-control">
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
