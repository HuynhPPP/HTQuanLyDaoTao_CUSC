<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SinhVienSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');
        
        for ($i = 0; $i < 100; $i++) {
            $maSV = '23' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            $gioiTinh = $faker->boolean;
            $ngaySinh = $faker->dateTimeBetween('-22 years', '-18 years');
            $ngayCap = $faker->dateTimeBetween('-2 years', 'now');
            
            DB::table('sinhvien')->insert([
                'MaSV' => $maSV,
                'HoTen' => $faker->name($gioiTinh ? 'male' : 'female'),
                'NgaySinh' => $ngaySinh->format('Y-m-d'),
                'GioiTinh' => $gioiTinh,
                'SoCCCD' => $faker->numerify('0#########'),
                'NgayCap' => $ngayCap->format('Y-m-d'),
                'NoiCap' => $faker->randomElement(['Công an tỉnh Cần Thơ', 'Công an tỉnh An Giang', 'Công an tỉnh Vĩnh Long', 'Công an tỉnh Đồng Tháp']),
                'Sdt' => $faker->numerify('0' . $faker->randomElement(['32', '33', '34', '35', '36', '37', '38', '39', '70', '79', '77', '76', '78', '83', '84', '85', '81', '82', '90', '93', '89', '88', '86', '96', '97', '98', '99']) . '#######'),
                'NoiSinh' => $faker->city,
                'DiaChi' => $faker->address,
                'Email' => $faker->email,
                'EmailCUSC' => $maSV . '@student.cusc.vn',
                'Zalo' => null,
                'HoTenNguoiThan' => $faker->name,
                'MoiQuanHe' => $faker->randomElement(['Cha', 'Mẹ', 'Anh', 'Chị', 'Em']),
                'SdtNguoiThan' => $faker->numerify('0#########'),
                'EmailNguoiThan' => $faker->email,
                'ZaloNguoiThan' => null,
                'NgayDangKi' => Carbon::now()->format('Y-m-d'),
            ]);
        }
    }
}
