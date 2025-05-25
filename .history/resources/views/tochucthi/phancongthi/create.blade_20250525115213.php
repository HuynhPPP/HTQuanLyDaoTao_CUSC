@extends('layouts.new_app.master')
@section('title', 'Phân công cán bộ coi thi')

@section('main-content')
<div class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>Phân công cán bộ - {{ $lichThi->monHoc->TenMH }} ({{ $lichThi->NgayThi }})</h1>
        <a href="{{ route('phancong.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Thêm phân công mới</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('phancong.store', $lichThi->MaLichThi) }}">
                            @csrf
                            <div class="form-group">
                                <label for="MaCB">Chọn cán bộ</label>
                                <select name="MaCB[]" class="form-control select2" multiple="">
                                    @foreach($availableCanBos as $cb)
                                        <option value="{{ $cb->MaCB }}">{{ $cb->HoTenCB }}</option>
                                    @endforeach
                                </select>
                                @error('MaCB')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="VaiTro">Vai trò</label>
                                <select name="VaiTro" class="form-control select2">
                                    <option value="Cán bộ coi thi">Cán bộ coi thi</option>
                                    <option value="Chấm thi">Chấm thi</option>
                                </select>
                                @error('VaiTro')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Phân công</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh sách đã phân công</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ tên</th>
                                        <th>Vai trò</th>
                                        <th>Ngày thi</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($phanCongList as $index => $pc)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pc->canBo->HoTenCB }}</td>
                                            <td>{{ $pc->VaiTro }}</td>
                                            <td>{{ $pc->lichThi->NgayThi->format('d/m/Y') }}</td>
                                            <td>
                                                <form action="{{ route('phancong.destroy', [$lichThi->MaLichThi, $pc->MaPhanCong]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn hủy phân công này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hủy</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có phân công</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Chọn cán bộ",
            allowClear: true
        });
    });
</script>
@endpush