@extends('layouts.new_app.master')
@section('title', 'Phân công thi')

@section('main-content')
<div class="section">
    <div class="section-header">
        <h1>Phân công cán bộ - {{ $lichThi->monHoc->TenMH }} ({{ $lichThi->NgayThi }})</h1>
    </div>

    <div class="section-body">

        {{-- Form phân công --}}
        <form method="POST" action="{{ route('phancong.store', $lichThi->id) }}">
            @csrf
            <div class="form-group">
                <label for="MaCB">Chọn cán bộ</label>
                <select name="MaCB" class="form-control" required>
                    <option value="">-- Chọn --</option>
                    @foreach($canBos as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->HoTen }}</option>
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

        {{-- Danh sách đã phân công --}}
        <hr>
        <h5 class="mt-4">Danh sách cán bộ đã được phân công:</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Vai trò</th>
                    <th>Ngày phân công</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phanCongs as $index => $pc)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pc->canBo->HoTen ?? 'Không rõ' }}</td>
                        <td>{{ $pc->VaiTro }}</td>
                        <td>{{ $pc->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Chưa có phân công</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
