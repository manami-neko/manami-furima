<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;

class CommentTest extends TestCase
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
    public function test_ログイン済みのユーザーはコメントを送信できる(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/comment", [
                'content' => 'これはテストコメントです',
            ]);

        $response->assertRedirect(); // 投稿後リダイレクトするはず
        $comment = Comment::where('user_id', $this->user->id)
            ->where('item_id', $this->item->id)
            ->first();

        $this->assertNotNull($comment);
        $this->assertEquals('これはテストコメントです', $comment->content);
    }

    public function test_ログイン前のユーザーはコメントを送信できない(): void
    {
        $response = $this->post("/items/{$this->item->id}/comment", [
            'content' => 'ログインなしコメント',
        ]);

        $response->assertRedirect('/login');

        // モデル経由で存在確認
        $comment = Comment::where('content', 'ログインなしコメント')->first();
        $this->assertNull($comment);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/comment", [
                'content' => '',
            ]);

        $response->assertSessionHasErrors(['content']);
    }

    public function test_コメントが255字以上の場合、バリデーションメッセージが表示される(): void
    {
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/comment", [
                'content' => $longComment,
            ]);

        $response->assertSessionHasErrors(['content']);
    }
}
