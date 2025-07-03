<div class="table-responsive">
    @foreach ($theoMon as $maMH => $diems)
        @php
            $tenMH = \App\Models\MonHoc::find($maMH)->TenMH ?? 'Môn không xác định';
        @endphp
        <h5 class="mt-4">{{ $tenMH }} ({{ $maMH }})</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Lý thuyết</th>
                    <th>Thực hành</th>
                    <th>Dự án</th>
                    <th>Tổng điểm</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diems as $diem)
                    <tr>
                        <td>{{ $diem->MaSV }}</td>
                        <td>{{ $diem->sinhVien->HoTen ?? '' }}</td>
                        <td>{{ number_format($diem->DiemLyThuyet ?? 0, 2) }}</td>
                        <td>{{ number_format($diem->DiemThucHanh ?? 0, 2) }}</td>
                        <td>{{ number_format($diem->DiemDuAn ?? 0, 2) }}</td>
                        <td>{{ number_format($diem->DiemTong ?? 0, 2) }}</td>
                        <td>{{ $diem->GhiChu }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
