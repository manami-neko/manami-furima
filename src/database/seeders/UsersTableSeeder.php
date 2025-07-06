<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            [
                'name' => 'テスト太郎',
                'email' => 'test@12345.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'テスト花子',
                'email' => 'test@6789.com',
                'password' => bcrypt('12345678'),
            ],
        ];
        DB::table('users')->insert($param);
    }
}
