<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mypage;
use App\Models\Item;


class MypageController extends Controller
{
    public function createProfile()
    {
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        if (!$mypage) {
            // 初期値を設定
            $mypage = [
                'user_id',
                'image' => 'Ellipse.1.png', // 例: デフォルト画像
                'postal_code' => '',
                'address' => '',
                'building' => '',
            ];
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
        $data['image'] = $request->image->store('images', 'public');
        // dump($data);
        Mypage::create($data);

        return redirect('/mypage');
    }

    public function index()
    {
        $items = Item::all();
        return view('mypages.profile', compact('items'));
    }

    public function editProfile()
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

    public function updateProfile()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }
}
