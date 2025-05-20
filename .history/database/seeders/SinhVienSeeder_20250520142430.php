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
        $faker = Faker::create();
        
        // Danh sách họ phổ biến
        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ'];
        
        // Danh sách tên đệm và tên
        $tenDem = ['Văn', 'Thị', 'Hoàng', 'Hữu', 'Đức', 'Minh', 'Thành', 'Công', 'Quang'];
        $ten = ['An', 'Bình', 'Cường', 'Dũng', 'Em', 'Phúc', 'Giang', 'Hùng', 'Khang', 'Linh', 'Mai', 'Nam'];
        
        // Danh sách tỉnh thành ĐBSCL
        $tinhThanh = [
            'Cần Thơ', 'An Giang', 'Bạc Liêu', 'Cà Mau', 'Đồng Tháp', 
            'Hậu Giang', 'Kiên Giang', 'Long An', 'Sóc Trăng', 'Tiền Giang', 
            'Trà Vinh', 'Vĩnh Long', 'Bến Tre'
        ];
        
        for ($i = 0; $i < 100; $i++) {
            $maSV = '23' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            $gioiTinh = $faker->boolean;
            $ngaySinh = $faker->dateTimeBetween('-22 years', '-18 years');
            $ngayCap = $faker->dateTimeBetween('-2 years', 'now');
            
            // Tạo họ tên Việt Nam
            $hoTen = $faker->randomElement($ho) . ' ' . 
                     $faker->randomElement($tenDem) . ' ' . 
                     $faker->randomElement($ten);
            
            // Tạo CCCD 12 số
            $soCCCD = '0' . $faker->numberBetween(1, 9) . $faker->numerify('##########');
            
            // Tạo địa chỉ Việt Nam
            $diaChi = $faker->numberBetween(1, 999) . ' ' . 
                      $faker->randomElement(['Nguyễn Văn Cừ', '3/2', '30/4', 'Nguyễn Văn Linh', 'Mậu Thân']) . ', ' . 
                      $faker->randomElement(['Ninh Kiều', 'Bình Thủy', 'Cái Răng', 'Ô Môn']) . ', ' . 
                      'Cần Thơ';
            
            DB::table('sinhvien')->insert([
                'MaSV' => $maSV,
                'HoTen' => $hoTen,
                'NgaySinh' => $ngaySinh->format('Y-m-d'),
                'GioiTinh' => $gioiTinh,
                'SoCCCD' => $soCCCD,
                'NgayCap' => $ngayCap->format('Y-m-d'),
                'NoiCap' => 'Công an ' . $faker->randomElement($tinhThanh),
                'Sdt' => '0' . $faker->randomElement(['32', '33', '34', '35', '36', '37', '38', '39', '70', '79', '77', '76', '78', '83', '84', '85', '81', '82', '90', '93', '89', '88', '86', '96', '97', '98', '99']) . $faker->numerify('#######'),
                'NoiSinh' => $faker->randomElement($tinhThanh),
                'DiaChi' => $diaChi,
                'Email' => strtolower(str_replace(' ', '', $hoTen)) . '@gmail.com',
                'EmailCUSC' => $maSV . '@student.cusc.vn',
                'Zalo' => null,
                'HoTenNguoiThan' => $faker->randomElement($ho) . ' ' . 
                                   $faker->randomElement($tenDem) . ' ' . 
                                   $faker->randomElement($ten),
                'MoiQuanHe' => $faker->randomElement(['Cha', 'Mẹ', 'Anh', 'Chị', 'Em']),
                'SdtNguoiThan' => '0' . $faker->randomElement(['32', '33', '34', '35', '36', '37', '38', '39', '70', '79', '77', '76', '78', '83', '84', '85', '81', '82', '90', '93', '89', '88', '86', '96', '97', '98', '99']) . $faker->numerify('#######'),
                'EmailNguoiThan' => $faker->email,
                'ZaloNguoiThan' => null,
                'NgayDangKi' => Carbon::now()->format('Y-m-d'),
            ]);
        }
    }
}
