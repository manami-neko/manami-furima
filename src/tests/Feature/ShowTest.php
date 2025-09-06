<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Mypage;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）(): void
    {
        // ユーザーとマイページ作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        Mypage::create([
            'user_id' => $user->id,
            'image' => 'images/test.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-1',
            'building' => 'テストビル101',
        ]);

        // Seederで作成されたものを利用
        $condition = Condition::first();
        $category = Category::first();

        // 商品作成
        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'detail' => '商品の説明文です',
            'price' => 1234,
            'image' => 'images/y.jpg',
            'status' => 'available',
        ]);

        // カテゴリを紐付け
        $item->categories()->sync([$category->id]);

        // コメント作成
        $comment = Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'とても良い商品です',
        ]);

        // アクセス
        $response = $this->get("/items/{$item->id}");

        // ステータス確認
        $response->assertStatus(200);

        // 必要な情報が表示されているか確認
        $response->assertSee($item->name)
            ->assertSee($item->brand)
            ->assertSee(number_format($item->price))
            ->assertSee($item->detail)
            ->assertSee($condition->content)
            ->assertSee($category->content)
            ->assertSee($comment->content)
            ->assertSee($user->name);
    }

    public function test_複数選択されたカテゴリが表示されているか(): void
    {
        $user = User::create([
            'name' => 'カテゴリユーザー',
            'email' => 'category@example.com',
            'password' => bcrypt('password123'),
        ]);

        $condition = Condition::first();

        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '複数カテゴリの商品',
            'brand' => 'ブランドX',
            'detail' => '説明テキスト',
            'price' => 500,
            'image' => 'items/test.png',
            'status' => 'available',
        ]);

        $categories = Category::take(3)->get(); // Seederの最初の3つ
        $item->categories()->sync($categories->pluck('id'));

        $response = $this->get("/items/{$item->id}");

        $response->assertStatus(200);

        foreach ($categories as $cat) {
            $response->assertSee($cat->content);
        }
    }
}
