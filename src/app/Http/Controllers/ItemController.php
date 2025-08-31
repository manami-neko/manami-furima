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
use App\Http\Requests\SellRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        $keyword = $request->query('keyword'); // 検索ワード取得

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                // ログインしていない場合、空のコレクションを渡す
                $items = collect();
            } else {
                // いいねした商品をクエリで取得
                $items = $user->likes()->with('item')->get()->map(fn($like) => $like->item)->filter();

                // コレクションで検索ワードフィルター
                if ($keyword) {
                    $items = $items->filter(fn($item) => str_contains($item->name, $keyword));
                }
            }
        } else {
            // マイリスト以外（全商品から自分の出品を除外）
            $query = Item::query();
            if ($user) {
                $query->where('user_id', '<>', $user->id);
            }

            // 検索ワードがあれば部分一致
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->get();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
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

    public function createSell()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        $item = new Item(); // 空のモデルを渡す（フォームで old() と併用可）

        $user = Auth::user();

        return view('items/sell', compact('item', 'categories', 'conditions'));
    }

    public function storeSell(SellRequest $request)
    {
        $item = new Item();

        // 画像アップロード
        $path = $request->file('image')->store('items', 'public');
        $item->image = $path;

        // フォームから送られてきた値をセット
        $item->user_id = auth()->id();
        $item->condition_id = $request->condition_id;
        $item->name = $request->name;
        $item->brand = $request->brand;
        $item->detail = $request->detail;
        $item->price = $request->price;
        $item->status = 'available'; // 初期状態を available にするなど


        $item->save();

        // カテゴリー紐づけ（多対多）
        $item->categories()->sync($request->category_ids);

        return redirect('mypage')->with('status');
    }
}
