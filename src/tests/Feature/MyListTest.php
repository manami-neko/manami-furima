<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Database\Seeders\ConditionsTableSeeder;

class MyListTest extends TestCase
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
    public function test_いいねした商品だけが表示される(): void
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $condition = Condition::first();

        // 他ユーザー作成
        $otherUser1 = User::create([
            'name' => '他ユーザー1',
            'email' => 'other1@example.com',
            'password' => bcrypt('password'),
        ]);

        $otherUser2 = User::create([
            'name' => '他ユーザー2',
            'email' => 'other2@example.com',
            'password' => bcrypt('password'),
        ]);

        // 他ユーザーが出品したアイテムを作成
        $likedItem = Item::create([
            'user_id' => $otherUser1->id, // 他ユーザー1
            'condition_id' => $condition->id,
            'name' => 'いいね商品',
            'brand' => 'ブランドA',
            'detail' => '詳細A',
            'price' => 1000,
            'image' => 'images/a.jpg',
            'status' => 'available',
        ]);

        $notLikedItem = Item::create([
            'user_id' => $otherUser2->id, // 他ユーザー2
            'condition_id' => $condition->id,
            'name' => '未いいね商品',
            'brand' => 'ブランドB',
            'detail' => '詳細B',
            'price' => 2000,
            'image' => 'images/b.jpg',
            'status' => 'available',
        ]);

        $user->likes()->create(['item_id' => $likedItem->id]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee($likedItem->name);
        $response->assertDontSee($notLikedItem->name);
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
            'status' => 'sold',
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
        $response->assertSee($item->name);
    }


    public function test_自分が出品した商品は表示されない(): void
    {
        // 出品者（テストユーザー）を作成してログイン
        $user = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        // 条件を取得
        $condition = Condition::first();

        // 自分の商品を作成
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

        // 他ユーザーを作成
        $otherUser = User::create([
            'name' => '他ユーザー',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
        ]);

        // 他ユーザーの商品を作成
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

        // 商品一覧ページを取得
        $response = $this->get('/');

        // 他人の商品は表示される
        $response->assertSee($otherItem->name);

        // 自分の商品は表示されない
        $response->assertDontSee($myItem->name);
    }

    public function 未認証の場合は何も表示されない(): void
    {
        $item = Item::create();

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee($item->name);
    }
}
