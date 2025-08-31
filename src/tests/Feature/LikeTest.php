<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なシーダーを実行
        $this->seed(UsersTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 先頭のユーザーと商品を取得して利用
        $this->user = User::first();
        $this->item = Item::first();
    }
    /**
     * A basic feature test example.
     */
    public function test_いいねアイコンを押下することによって、いいねした商品として登録することができる。(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }

    public function test_追加済みのアイコンは色が変化する(): void
    {
        // 先にいいね
        $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response = $this->actingAs($this->user)
            ->get("/items/{$this->item->id}");

        $response->assertStatus(200);
        $response->assertSee('liked'); // Blade 側の <span class="liked">★</span>
    }

    public function test_再度いいねアイコンを押下することによって、いいねを解除することができる。(): void
    {
        // いいね
        $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        // 解除
        $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }
}
