<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mypage;
use App\Models\Item;
use App\Http\Requests\EditRequest;
use App\Http\Requests\AddressRequest;


class MypageController extends Controller
{
    public function createProfile()
    {
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        if (!$mypage) {
            $mypage = new Mypage();
            $mypage->user_id = $user->id;
            $mypage->image = 'images/Ellipse 1.png';
            $mypage->postal_code = '';
            $mypage->address = '';
            $mypage->building = '';
        }

        return view('users.login', compact('mypage'));
    }

    public function storeProfile(Request $request)
    {
        $data = $request->only(
            [
                'image',
                'postal_code',
                'address',
                'building',
            ]
        );
        // dump($request->all());
        $data['user_id'] = auth()->id();
        // dump($data);
        // 画像がアップロードされている場合のみ保存
        if ($request->hasFile('image')) {
            $data['image'] = $request->image->store('images', 'public');
        } else {
            // 画像がなければデフォルト画像を設定
            $data['image'] = 'images/Ellipse 1.png';
        }
        // dump($data);
        Mypage::create($data);

        return redirect('/mypage');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        $items = Item::all();

        // クエリパラメータの取得（例：buy or sell）
        $tab = $request->query('tab');

        // tab に応じたアイテム取得
        if ($tab === 'sell') {
            $items = $user->items ?? collect(); // 出品商品
        } elseif ($tab === 'buy') {
            $items = $user->purchases ?? collect(); // 購入商品
        } else {
            $items = Item::latest()->get(); // 全てのアイテム（新しい順）
        }

        return view('mypages.profile', compact('items', 'mypage', 'user', 'tab'));
    }

    public function editProfile(Request $request)
    {
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        if (!$mypage) {
            $mypage = new Mypage();
            $mypage->name = $user->name; // ユーザーの名前を初期値にするなど
            $mypage->postal_code = '';
            $mypage->address = '';
            $mypage->building = '';
            $mypage->image = 'Ellipse.1.png';
        }

        return view('mypages.edit', compact('mypage', 'user'));
    }

    public function updateProfile(EditRequest $request)
    {
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        $mypage->postal_code = $request->input('postal_code');
        $mypage->address = $request->input('address');
        $mypage->building = $request->input('building');

        $path = $request->image->store('images', 'public');
        $mypage->image = $path;

        $mypage->save();

        return redirect('/mypage');
    }

    public function editAddress(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        return view('mypages.address', compact('item', 'mypage'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        session([
            'purchase_address' => [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ]
        ]);



        return redirect()->route('purchase.create', ['item_id' => $item_id]);
    }
}
