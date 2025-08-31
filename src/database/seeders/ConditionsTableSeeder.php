<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            ['id' => 1, 'content' => '良好'],
            ['id' => 2, 'content' => '目立った傷や汚れなし'],
            ['id' => 3, 'content' => 'やや傷や汚れあり'],
            ['id' => 4, 'content' => '状態が悪い'],
        ];
        DB::table('conditions')->insert($param);
    }
}
