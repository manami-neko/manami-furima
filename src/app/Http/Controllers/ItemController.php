<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Mypage;
use App\Models\Like;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items/index', compact('items'));
    }

    public function show($itemId)
    {
        $item = Item::with(['categories', 'condition', 'likes'])->findOrFail($itemId);
        $categories = Category::all();
        $conditions = Condition::all();

        $user = Auth::user();

        $isLiked = auth()->check() && $item->likes->contains('user_id', auth()->id());

        return view('items/show', compact('item', 'categories', 'conditions', 'isLiked'));
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
