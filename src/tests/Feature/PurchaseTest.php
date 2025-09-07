<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Mypage;
use App\Models\Purchase;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーを実行してデータを用意
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $this->user = User::first();
        $this->item = Item::first();

        // テスト用に必ず Mypage を作る
        if (!Mypage::where('user_id', $this->user->id)->exists()) {
            Mypage::create([
                'user_id' => $this->user->id,
                'image' => 'images/default.png',
                'postal_code' => '000-0000',
                'address' => '東京都',
                'building' => 'テストビル',
            ]);
        }
    }

    /**
     * A basic feature test example.
     */
    public function test_「購入する」ボタンを押下すると購入が完了する(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('purchase.store', ['item_id' => $this->item->id]), [
                'payment' => 'credit_card',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'テストビル',
            ]);

        $response->assertRedirect(route('items.index'));

        // 購入情報がDBに保存されているか確認
        $purchase = Purchase::where('user_id', $this->user->id)
            ->where('item_id', $this->item->id)
            ->first();

        $this->assertNotNull($purchase);
        $this->assertEquals('credit_card', $purchase->payment);

        // 商品ステータスが Sold に更新されているか確認
        $this->item->refresh();
        $this->assertEquals('Sold', $this->item->status);
    }

    public function test_購入した商品は商品一覧画面にて「Sold」と表示される(): void
    {
        // 先に購入状態にする
        $this->actingAs($this->user)
            ->post("/purchase/{$this->item->id}", [
                'payment' => 'credit_card',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'テストビル',
            ]);

        // Blade に渡す items を最新の DB 状態で再取得
        $this->item->refresh();

        $response = $this->get('/');
        $response->assertSee('Sold'); // Blade 側に sold が表示されているか
    }

    public function test_「プロフィール・購入した商品一覧」に追加されている(): void
    {
        // 先に購入状態にする
        $this->actingAs($this->user)
            ->post("/purchase/{$this->item->id}", [
                'payment' => 'credit_card',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'テストビル',
            ]);

        // ログインして購入タブにアクセス
        $response = $this->actingAs($this->user)
            ->get('/mypage?tab=buy');

        $response->assertStatus(200);

        // シーダーで作った購入商品を取得
        $purchasedItems = $this->user->purchases()->with('item')->get()->map(fn($p) => $p->item);

        foreach ($purchasedItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image);
        }

        // ユーザーが購入していない商品は表示されない
        $nonPurchasedItems = Item::whereNotIn('id', $purchasedItems->pluck('id'))->get();
        foreach ($nonPurchasedItems as $item) {
            $response->assertDontSee($item->name);
        }
    }
}
