<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MypagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            [
                'user_id' => 1,
                'image' => 'images/Ellipse 1.png',
                'postal_code' => '123-4567',
                'address' => '東京都',
                'building' => 'メゾン202',
            ],
            [
                'user_id' => 2,
                'image' => 'images/Ellipse 1.png',
                'postal_code' => '123-4567',
                'address' => '長野県',
                'building' => '',
            ],
        ];
        DB::table('mypages')->insert($param);
    }
}
