<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\ConditionsTableSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $user = User::first();
        $this->actingAs($user);

        // 商品出品画面表示確認
        $response = $this->get('/sell');
        $response->assertStatus(200);
        $response->assertSee('カテゴリー');
        $response->assertSee('商品の状態');

        // ストレージをモック
        Storage::fake('public');

        // GD不要のダミー画像ファイルを作成
        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        // 商品出品フォーム送信
        $response = $this->post('/sell', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'detail' => '商品の説明です',
            'price' => 1000,
            'condition_id' => 1,
            'category_ids' => [1], // カテゴリーID
            'image' => $file,
        ]);

        $response->assertRedirect('/mypage');

        // 商品がDBに保存されているか確認
        $item = Item::where('name', 'テスト商品')->first();
        $this->assertNotNull($item);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'detail' => '商品の説明です',
            'price' => 1000,
            'condition_id' => 1,
        ]);

        // 画像が保存されているか確認
        Storage::disk('public')->assertExists($item->image);

        // pivotテーブルでカテゴリ紐付けを確認
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => 1,
        ]);
    }
}
