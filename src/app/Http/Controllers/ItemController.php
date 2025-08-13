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
use App\Models\Purchase;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                // ログインしていない場合、空のコレクションを渡す
                $items = collect();
                // return redirect()->route('login'); // Fortifyのloginルート
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

    public function comment(CommentRequest $request, $itemId)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
            'content' => $request->content,
        ]);

        return back();
    }


    public function createPurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $mypage = Mypage::where('user_id', $user->id)->first();

        $sold = $item->status === 'sold';


        return view('items/purchase', compact('item', 'mypage', 'sold'));
    }

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // if ($item->status === 'sold') {
        //     return back()->withErrors(['item' => 'この商品はすでに売り切れです']);
        // }

        // 購入情報の保存
        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment' => $request->payment,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // 商品ステータスを確実に更新
        $item->status = 'sold';
        $item->save();

        return redirect()->route('items.index');
    }
    // public function purchasedItems()
    // {
    //     $purchases = Purchase::with('item')
    //         ->where('user_id', auth()->id())
    //         ->get();

    //     return view('mypage.purchased', compact('purchases'));
    // }


    public function createSell()
    {
        return view('items/sell');
    }

    public function storeSell()
    {
        return view('items/sell');
    }
}
