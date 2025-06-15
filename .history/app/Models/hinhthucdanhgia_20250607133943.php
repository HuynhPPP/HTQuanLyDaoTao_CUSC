namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhThucDanhGia extends Model
{
    use HasFactory;

    protected $table = 'hinh_thuc_danh_gia';

    protected $fillable = [
        'MaChuongTrinh',
        'HinhThuc',
        'TiLePhanTram',
        'SoBaiThi',
        'DiemMoiBai',
        'ThoiGian',
        'DonViThoiGian',
    ];

    // Quan hệ với Chương trình
    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }
}
