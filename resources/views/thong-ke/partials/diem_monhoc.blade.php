@foreach ($theoMon as $maMH => $diems)
    @php
        $tenMH = \App\Models\MonHoc::find($maMH)->TenMH ?? 'Môn không xác định';
    @endphp
    <div class="card">
        <div class="card-header" role="button" data-toggle="collapse" data-target="#monhoc-{{ $maMH }}"
            aria-expanded="true">
            <h4>{{ $tenMH }} ({{ $maMH }})</h4>
        </div>
        <div class="card-body collapse show" id="monhoc-{{ $maMH }}">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Lý thuyết</th>
                            <th>Thực hành</th>
                            <th>Dự án</th>
                            <th>Tổng điểm (theo CTĐT)</th>
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
                                <td>
                                    {{ number_format($diem->TongDiemTinhLai ?? ($diem->DiemTong ?? 0), 2) }}
                                </td>
                                <td>{{ $diem->GhiChu }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach
