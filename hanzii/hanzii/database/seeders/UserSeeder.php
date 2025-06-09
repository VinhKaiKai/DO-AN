<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        DB::table('users')->insert([
            'name' => 'Admin Hệ thống',
            'email' => 'admin@hanzii.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tài khoản giáo viên
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "Giáo viên $i",
                'email' => "giaovien$i@hanzii.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo tài khoản trợ giảng
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'name' => "Trợ giảng $i",
                'email' => "trogiang$i@hanzii.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo tài khoản học viên
        for ($i = 1; $i <= 50; $i++) {
            DB::table('users')->insert([
                'name' => "Học viên $i",
                'email' => "hocvien$i@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 