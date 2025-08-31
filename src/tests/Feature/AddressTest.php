<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mypage;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\MypagesTableSeeder;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Item $item;
    protected Mypage $mypage;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なシーダーを実行
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(MypagesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 先頭のユーザーと商品を取得して利用
        $this->user = User::first();
        $this->item = Item::first();
        $this->mypage = Mypage::first();
    }
    /**
     * A basic feature test example.
     */
    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている(): void
    {
        $this->actingAs($this->user);

        // 住所を更新
        $response = $this->post("/purchase/address/{$this->item->id}", [
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区神宮前1-1-1',
            'building' => '渋谷ビル201',
        ]);

        $response->assertRedirect("/purchase/{$this->item->id}");

        // セッションに保存されているか確認
        $this->assertEquals('150-0001', session('purchase_address.postal_code'));
        $this->assertEquals('東京都渋谷区神宮前1-1-1', session('purchase_address.address'));
        $this->assertEquals('渋谷ビル201', session('purchase_address.building'));
    }

    public function test_購入した商品に送付先住所が紐づいて登録される(): void
    {
        $this->actingAs($this->user);

        // 購入処理時の送付先をセッションに保存
        session([
            'purchase_address' => [
                'postal_code' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'building' => '梅田タワー501',
            ]
        ]);

        // 実際の購入処理
        $response = $this->post("/purchase/{$this->item->id}", [
            'payment' => 'card',
            'postal_code' => '530-0001',
            'address' => '大阪府大阪市北区梅田1-1-1',
            'building' => '梅田タワー501',
        ]);



        // 購入履歴テーブルにレコードが保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'postal_code' => '530-0001',
            'address' => '大阪府大阪市北区梅田1-1-1',
            'building' => '梅田タワー501',
        ]);
    }
}
