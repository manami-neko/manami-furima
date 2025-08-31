<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される(): void
    {

        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/login')->post('/login', [
            'email' => '',                       // 未入力
        ]);

        // セッションのエラー（キーとメッセージ）を確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);

        // 実際の画面にエラーテキストが描画されていることも確認
        $this->followRedirects($response)
            ->assertSee('メールアドレスを入力してください');
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/login')->post('/login', [
            'password' => '',                       // 未入力
        ]);

        // セッションのエラー（キーとメッセージ）を確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);

        // 実際の画面にエラーテキストが描画されていることも確認
        $this->followRedirects($response)
            ->assertSee('パスワードを入力してください');
    }

    public function test_入力情報が間違っている場合、バリデーションメッセージが表示される(): void
    {

        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => 'WrongPass',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

        $this->followRedirects($response)
            ->assertSee('ログイン情報が登録されていません');
    }

    public function test_正しい情報が入力された場合、ログイン処理が実行される(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
