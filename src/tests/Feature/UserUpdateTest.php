<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mypage;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\MypagesTableSeeder;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Mypage $mypage;

    /**
     * A basic feature test example.
     */
    public function test_変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(MypagesTableSeeder::class);

        $this->user = User::first();
        $this->mypage = Mypage::first();

        // ログイン状態にする
        $response = $this->actingAs($this->user)->get('/mypage/profile');

        // ステータス確認
        $response->assertStatus(200);

        // シーダーで設定した初期値が表示されているか確認
        $response->assertSee($this->user->name);
        $response->assertSee($this->mypage->image);
        $response->assertSee($this->mypage->postal_code);
        $response->assertSee($this->mypage->address);
    }
}
