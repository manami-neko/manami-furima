<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Mypage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items/index', compact('items'));
    }

    public function show($itemId)
    {
        $item = Item::with(['categories', 'condition'])->findOrFail($itemId);
        $categories = Category::all();
        $conditions = condition::all();
        return view('items/show', compact('item', 'categories', 'conditions'));
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
