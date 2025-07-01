namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ThongKeHocTapExport implements FromCollection, WithHeadings, WithTitle
{
    protected $thongKe;

    public function __construct($thongKe)
    {
        $this->thongKe = $thongKe;
    }

    public function collection()
    {
        return collect([
            [
                'Tổng Số Sinh Viên' => $this->thongKe->tong_sinh_vien,
                'Sinh Viên Giỏi' => $this->thongKe->sinh_vien_gioi,
                'Sinh Viên Khá' => $this->thongKe->sinh_vien_kha,
                'Sinh Viên Trung Bình' => $this->thongKe->sinh_vien_trung_binh,
                'Sinh Viên Yếu' => $this->thongKe->sinh_vien_yeu,
                'Điểm Trung Bình' => $this->thongKe->diem_trung_binh_tong_khoa,
                'Tỷ Lệ Tốt Nghiệp' => $this->thongKe->ty_le_tot_nghiep . '%'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Tổng Số Sinh Viên',
            'Sinh Viên Giỏi',
            'Sinh Viên Khá',
            'Sinh Viên Trung Bình',
            'Sinh Viên Yếu',
            'Điểm Trung Bình',
            'Tỷ Lệ Tốt Nghiệp'
        ];
    }

    public function title(): string
    {
        return 'Thống Kê Học Tập';
    }
}
