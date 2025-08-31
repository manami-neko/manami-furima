<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ConditionsTableSeeder;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_全商品を取得できる(): void
    {
        $response = $this->get('/');

        $response->assertViewIs('items.index');
        $items = Item::all();

        // 順番に画面上に表示されているかチェック
        $response->assertSeeInOrder($items->pluck('name')->toArray());
    }

    public function test_購入済み商品は「Sold」と表示される(): void
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);

        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($buyer); // ビューに表示させたいユーザーは購入者

        $condition = Condition::first();

        $item = Item::create([
            'user_id' => $seller->id, // 出品者は自分じゃない
            'condition_id' => $condition->id,
            'name' => '売れた商品',
            'brand' => 'ブランドX',
            'detail' => '詳細X',
            'price' => 3000,
            'image' => 'images/x.jpg',
            'status' => 'Sold',
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
        $response->assertSee($item->name);
    }

    public function test_自分が出品した商品は表示されない(): void
    {
        $user = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $condition = Condition::first();

        $myItem = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '自分の商品',
            'brand' => 'ブランドY',
            'detail' => '詳細Y',
            'price' => 4000,
            'image' => 'images/y.jpg',
            'status' => 'available',
        ]);

        $otherUser = User::create([
            'name' => '他ユーザー',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
        ]);

        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => '他人の商品',
            'brand' => 'ブランドZ',
            'detail' => '詳細Z',
            'price' => 5000,
            'image' => 'images/z.jpg',
            'status' => 'available',
        ]);

        $response = $this->get('/');
        $response->assertSee($otherItem->name);
        $response->assertDontSee($myItem->name);
    }
}
