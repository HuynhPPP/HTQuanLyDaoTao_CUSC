<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SinhVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        for ($i = 0; $i < 100; $i++) {
            $maSV = '23' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $gioiTinh = $faker->boolean;
            
            DB::table('sinhvien')->insert([
                'MaSV' => $maSV,
                'HoTen' => $faker->name($gioiTinh ? 'male' : 'female'),
                'GioiTinh' => $gioiTinh,
                'NgaySinh' => $faker->dateTimeBetween('-22 years', '-18 years')->format('Y-m-d'),
                'NoiSinh' => $faker->city,
                'DiaChi' => $faker->address,
                'Email' => $faker->email,
                'Sdt' => $faker->numerify('0#########'),
                'CCCD' => $faker->numerify('0##############'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
