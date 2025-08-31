<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mypage;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\MypagesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Mypage $mypage;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder を実行
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(MypagesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 先頭のユーザーとマイページを取得
        $this->user = User::first();
        $this->mypage = Mypage::first();
    }

    /**
     * A basic feature test example.
     */
    public function test_必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）(): void
    {
        $this->actingAs($this->user);

        // マイページにアクセス
        $response = $this->get('/mypage');

        $response->assertStatus(200);

        // プロフィール画像・ユーザー名が表示されていることを確認
        $response->assertSee($this->user->name);
        $response->assertSee($this->mypage->image);

        // 出品商品一覧の確認
        foreach ($this->user->items as $item) {
            $response->assertSee($item->name);
        }

        // 購入商品一覧（もしあれば）も確認
        foreach ($this->user->purchases as $purchase) {
            $response->assertSee($purchase->item->name);
        }
    }
}
