<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Mypage;
use App\Models\Like;
use App\Models\Comment;

class ItemController extends Controller
{
    // public function index(Request $request)
    // {
    //     $tab = $request->query('tab');

    //     if ($tab === 'mylist') {
    //         if (!Auth::check()) {
    //             return redirect()->route('login'); // Fortifyのloginルート
    //         }

    //         // マイリスト用のデータ取得
    //         $items = Auth::user()->likes()->with('item')->get()->pluck('item'); // 例
    //     } else {
    //         // おすすめ商品の取得
    //         $items = Item::all();
    //     }

    //     return view('items.index', compact('items'));
    // }

    public function index(Request $request)
    {
        $tab = $request->query('tab');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login'); // Fortifyのloginルート
            }

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // ログインしていれば「いいね」された商品一覧、未ログインなら空
            $items = $user ? $user->likes()->with('item')->get()->pluck('item') : collect();
        } else {
            // おすすめ商品の取得
            $items = Item::all();
        }

        return view('items.index', compact('items'));
    }

    public function show($itemId)
    {
        $item = Item::with(['categories', 'condition', 'likes', 'comments.user.mypage',])->findOrFail($itemId);
        $categories = Category::all();
        $conditions = Condition::all();

        $user = Auth::user();
        $mypage = $user ? Mypage::where('user_id', $user->id)->first() : null;

        $isLiked = auth()->check() && $item->likes->contains('user_id', auth()->id());

        return view('items/show', compact('item', 'categories', 'conditions', 'isLiked', 'mypage'));
    }

    public function like($itemId)
    {
        $user = auth()->user();
        $item = Item::findOrFail($itemId);

        $like = Like::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if ($like) {
            $like->delete(); // いいね解除
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        return back();
    }

    public function comment(Request $request, $itemId)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
            'content' => $request->comment,
        ]);

        return back();
    }

    public function createSell()
    {
        return view('items/sell');
    }

    public function storeSell()
    {
        return view('items/sell');
    }

    public function createPurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        return view('items/purchase', compact('item', 'mypage'));
    }

    public function storePurchase()
    {
        return view('items/purchase');
    }
}
