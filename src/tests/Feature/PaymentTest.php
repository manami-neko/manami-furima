<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Mypage;
use App\Models\Purchase;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\MypagesTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ConditionsTableSeeder;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_小計画面で変更が反映される(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(MypagesTableSeeder::class);
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        $user = User::first();
        $item = Item::first();
        $mypage = Mypage::first();

        $this->actingAs($user);

        // 支払い方法を選択して購入フォーム送信
        $formData = [
            'item_id' => $item->id,
            'payment' => 'card', // フォームのvalueに合わせる
            'postal_code' => $mypage->postal_code,
            'address' => $mypage->address,
            'building' => $mypage->building,
        ];

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), $formData);

        // 購入完了ページなどにリダイレクトされることを確認
        $response->assertStatus(302);

        // データベースに正しく保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'card',
        ]);
    }
}
