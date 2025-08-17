<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');

        $param = [
            [
                'user_id' => $users->random(),
                'condition_id' => 1,
                'image' => 'images/Armani+Mens+Clock.jpg',
                'name' => '腕時計',
                'price' => '15000',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 2,
                'image' => 'images/HDD+Hard+Disk.jpg',
                'name' => 'HDD',
                'price' => '5000',
                'detail' => '高速で信頼性の高いハードディスク',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 3,
                'image' => 'images/iLoveIMG+d.jpg',
                'name' => '玉ねぎ3束',
                'price' => '300',
                'detail' => '新鮮な玉ねぎ3束のセット',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 4,
                'image' => 'images/Leather+Shoes+Product+Photo.jpg',
                'name' => '革靴',
                'price' => '4000',
                'detail' => 'クラシックなデザインの革靴',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 1,
                'image' => 'images/Living+Room+Laptop.jpg',
                'name' => 'ノートPC',
                'price' => '45000',
                'detail' => '高性能なノートパソコン',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 2,
                'image' => 'images/Music+Mic+4632231.jpg',
                'name' => 'マイク',
                'price' => '8000',
                'detail' => '高音質のレコーディング用マイク',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 3,
                'image' => 'images/Purse+fashion+pocket.jpg',
                'name' => 'ショルダーバッグ',
                'price' => '3500',
                'detail' => 'おしゃれなショルダーバッグ',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 4,
                'image' => 'images/Tumbler+souvenir.jpg',
                'name' => 'タンブラー',
                'price' => '500',
                'detail' => '使いやすいタンブラー',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 1,
                'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
                'name' => 'コーヒーミル',
                'price' => '4000',
                'detail' => '手動のコーヒーミル',
            ],
            [
                'user_id' => $users->random(),
                'condition_id' => 2,
                'image' => 'images/外出メイクアップセット.jpg',
                'name' => 'メイクセット',
                'price' => '2500',
                'detail' => '便利なメイクアップセット',
            ],
        ];
        DB::table('items')->insert($param);
    }
}
