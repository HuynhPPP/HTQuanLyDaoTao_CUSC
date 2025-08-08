@php
    // Đảm bảo thứ tự xếp loại cố định
    $xepLoaiOrder = ['Giỏi', 'Khá', 'Đạt'];
    
    // Đếm số lượng sinh viên theo từng xếp loại
    $xepLoaiCounts = collect($tongKet ?? [])
        ->groupBy('XepLoai')
        ->map->count()
        ->toArray();

    // Sắp xếp và lọc các xếp loại
    $xepLoaiData = collect($xepLoaiOrder)
        ->mapWithKeys(
            fn($loai) => [
                $loai => $xepLoaiCounts[$loai] ?? 0,
            ],
        )
        ->filter(fn($count) => $count > 0);

    $xepLoaiLabels = $xepLoaiData->keys()->toArray();
    $xepLoaiValues = $xepLoaiData->values()->toArray();

    // Debug: In ra thông tin để kiểm tra
    \Log::info('Xếp loại Labels (Processed):', $xepLoaiLabels);
    \Log::info('Xếp loại Values (Processed):', $xepLoaiValues);
@endphp

<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                 <h4>Chi tiết học lực sinh viên (điểm TB theo CTĐT)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Mã SV</th>
                                <th>Họ tên</th>
                                <th>Điểm TB</th>
                                <th>Xếp loại</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tongKet ?? [] as $sv)
                                <tr>
                                    <td>{{ $sv['MaSV'] }}</td>
                                    <td>{{ $sv['HoTen'] }}</td>
                                     <td>{{ number_format($sv['DiemTB'], 2) }}</td>
                                    <td>
                                        <span>
                                            {{ $sv['XepLoai'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

