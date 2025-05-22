<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GiaoVienSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Danh sách họ phổ biến
        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ'];
        $tenDem = ['Văn', 'Thị', 'Hoàng', 'Hữu', 'Đức', 'Minh', 'Thành', 'Công', 'Quang'];
        $ten = ['An', 'Bình', 'Cường', 'Dũng', 'Em', 'Phúc', 'Giang', 'Hùng', 'Khang', 'Linh', 'Mai', 'Nam'];

        $chucVu = ['Giảng viên', 'Trưởng bộ môn', 'Phó khoa', 'Giảng viên chính'];
        $chuyenNganh = ['Khoa học máy tính', 'Công nghệ phần mềm', 'Hệ thống thông tin', 'Mạng máy tính', 'Trí tuệ nhân tạo'];

        for ($i = 0; $i < 6; $i++) {
            $maGV = 'GV' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $hoTen = $faker->randomElement($ho) . ' ' . $faker->randomElement($tenDem) . ' ' . $faker->randomElement($ten);
            $gioiTinh = $faker->boolean;
            $email = strtolower(str_replace(['đ', 'Đ'], 'd', str_replace(' ', '', $hoTen))) . '@cusc.vn';
            $sdt = '0' . $faker->randomElement(['91', '93', '94', '96', '97', '98', '99']) . $faker->numerify('#######');

            DB::table('giaovien')->insert([
                'MaGV' => $maGV,
                'HoTenGV' => $hoTen,
                'GioiTinh' => $gioiTinh,
                'Email' => $email,
                'Sdt' => $sdt,
                'MaHV' => 'HV' . str_pad($i + 1, 4, '0', STR_PAD_LEFT), // ví dụ mã học vị
                'TenChucVu' => $faker->randomElement($chucVu),
                'MaDV' => 'DV' . str_pad($i + 1, 3, '0', STR_PAD_LEFT), // ví dụ mã đơn vị
                'MaBang' => 'BANG' . str_pad($i + 1, 3, '0', STR_PAD_LEFT), // ví dụ mã bằng cấp
                'LoaiGV' => $faker->randomElement(['CoHuu', 'MoiGiang']),
                'ChuyenNganh' => $faker->randomElement($chuyenNganh),
                'GhiChu' => null,
                'NgayBatDauCongTac' => $faker->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'),
                'NgayKetThucCongTac' => null, // đang còn làm
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
