<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class CategoryItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item = Item::find(1);
        $item->categories()->sync([1]);

        $item = Item::find(2);
        $item->categories()->sync([2]);

        $item = Item::find(3);
        $item->categories()->sync([10]);

        $item = Item::find(4);
        $item->categories()->sync([1]);

        $item = Item::find(5);
        $item->categories()->sync([2]);

        $item = Item::find(6);
        $item->categories()->sync([2]);

        $item = Item::find(7);
        $item->categories()->sync([1]);

        $item = Item::find(8);
        $item->categories()->sync([10]);

        $item = Item::find(9);
        $item->categories()->sync([10]);

        $item = Item::find(10);
        $item->categories()->sync([6]);
    }
}
