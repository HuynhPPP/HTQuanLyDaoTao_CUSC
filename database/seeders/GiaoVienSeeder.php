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

        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ'];
        $tenDem = ['Văn', 'Thị', 'Hoàng', 'Hữu', 'Đức', 'Minh', 'Thành', 'Công', 'Quang'];
        $ten = ['An', 'Bình', 'Cường', 'Dũng', 'Em', 'Phúc', 'Giang', 'Hùng', 'Khang', 'Linh', 'Mai', 'Nam'];

        // Lấy dữ liệu từ các bảng liên quan
        $hocVis = DB::table('hocvi')->pluck('MaHV')->toArray();
        $chucVus = DB::table('chucvu')->pluck('TenChucVu')->toArray();
        $donVis = DB::table('donvi')->pluck('MaDV')->toArray();
        $bangCaps = DB::table('bangcapcanbo')->pluck('MaBang')->toArray();

        $chuyenNganh = ['Khoa học máy tính', 'Công nghệ phần mềm', 'Hệ thống thông tin', 'Mạng máy tính', 'Trí tuệ nhân tạo'];
        $loaiGVs = ['CoHuu', 'MoiGiang'];

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
                'MaHV' => $faker->randomElement($hocVis),
                'TenChucVu' => 'Giảng viên',
                'MaDV' => $faker->randomElement($donVis),
                'MaBang' => $faker->randomElement($bangCaps),
                'LoaiGV' => $faker->randomElement($loaiGVs),
                'ChuyenNganh' => $faker->randomElement($chuyenNganh),
                'GhiChu' => null,
                'NgayBatDauCongTac' => $faker->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'),
                'NgayKetThucCongTac' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
