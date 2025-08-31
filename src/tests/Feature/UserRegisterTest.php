<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_名前が入力されていない場合、バリデーションメッセージが表示される(): void
    {
        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/register')->post('/register', [
            'name' => '',                       // 未入力
        ]);

        // セッションのエラー（キーとメッセージ）を確認
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);

        // 実際の画面にエラーテキストが描画されていることも確認
        $this->followRedirects($response)
            ->assertSee('お名前を入力してください');
    }

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される(): void
    {

        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/register')->post('/register', [
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
        $response = $this->from('/register')->post('/register', [
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

    public function test_パスワードが7文字以下の場合、バリデーションメッセージが表示される(): void
    {
        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        // セッションのエラー（キーとメッセージ）を確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);

        // 実際の画面にエラーテキストが描画されていることも確認
        $this->followRedirects($response)
            ->assertSee('パスワードは8文字以上で入力してください');
    }

    public function test_パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される(): void
    {
        // バリデーション失敗時に戻る元を明示
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password456', // 不一致
        ]);

        // セッションのエラー（キーとメッセージ）を確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);

        // 実際の画面にエラーテキストが描画されていることも確認
        $this->followRedirects($response)
            ->assertSee('パスワードと一致しません');
    }

    public function test_全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される(): void
    {
        // バリデーション失敗時に戻る元を明示
        $user = [
            'name' => 'テストユーザー',
            'email' => 'unique_user@example.com', // ユニークなメール
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ];

        $response = $this->post('/register', $user);

        // usersテーブルに name と email が保存されているか確認（パスワードはハッシュなので除外）
        $this->assertDatabaseHas('users', [
            'name'  => 'テストユーザー',
            'email' => 'unique_user@example.com',
        ]);

        // ログイン画面にリダイレクトされることを確認
        $response->assertRedirect('/profile');
    }
}
