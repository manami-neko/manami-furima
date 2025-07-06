<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items/index', compact('items'));
    }

    public function show($itemId)
    {
        $item = Item::findOrFail($itemId);
        $categories = Category::all();
        return view('items/show', compact('item', 'categories'));
    }
}
