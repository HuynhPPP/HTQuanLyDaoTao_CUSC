namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'sinh_vien';
    protected $fillable = [
        'ma_sv', 
        'ho_ten', 
        'ngay_sinh', 
        'gioi_tinh', 
        'lop_hoc', 
        'khoa', 
        'email', 
        'so_dien_thoai'
    ];

    // Quan hệ với điểm số
    public function scores()
    {
        return $this->hasMany(Score::class, 'ma_sv', 'ma_sv');
    }

    // Quan hệ với lớp học
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'lop_hoc', 'ma_lop');
    }

    // Phương thức tính điểm trung bình
    public function calculateGPA()
    {
        return $this->scores()->avg('diem_so');
    }

    // Phương thức phân loại học lực
    public function getAcademicPerformance()
    {
        $gpa = $this->calculateGPA();
        if ($gpa >= 8.0) return 'Giỏi';
        if ($gpa >= 6.5) return 'Khá';
        if ($gpa >= 5.0) return 'Trung bình';
        return 'Yếu';
    }
} 