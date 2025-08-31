<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Condition;
use Database\Seeders\ConditionsTableSeeder;

class SearchTest extends TestCase
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
    public function test_「商品名」で部分一致検索ができる(): void
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 検索対象データを直接作成
        Item::create([
            'user_id' => $user->id,
            'condition_id' => 1,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'price' => '15000',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
        ]);

        Item::create([
            'user_id' => $user->id,
            'condition_id' => 2,
            'image' => 'images/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'price' => '5000',
            'detail' => '高速で信頼性の高いハードディスク',
        ]);

        // keyword=時計 でアクセス
        $response = $this->get('/?keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_「検索状態がマイリストでも保持されている(): void
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $condition = Condition::first();

        // 商品作成
        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '検索用アイテムABC',
            'brand' => 'ブランドX',
            'detail' => '詳細テキスト',
            'price' => 1000,
            'status' => 'available',
            'image' => 'items/test.jpg',
        ]);

        // いいね登録
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // マイリスト検索ページにアクセス
        $response = $this->get('/?tab=mylist&keyword=ABC');

        // ビュー確認
        $response->assertStatus(200);
        $response->assertViewIs('items.index');

        // 検索キーワードが保持され、商品名が表示されていることを確認
        $response->assertSee('検索用アイテムABC');
    }
}
