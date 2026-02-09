<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    $tab = $request->query('tab', 'recommend');

    if ($tab === 'mylist') {
      $items = auth()->check()
        ? auth()->user()->likeItems()->with('categories')->get()
        : collect();
    } else {
      $items = Item::with('categories')->get();
    }
    return view('index', compact('items', 'tab'));
  }

  public function show($item_id)
  {
    $item = Item::with(['categories', 'comments.user'])->withCount('likes')->findOrFail($item_id);
    return view('item_detail', compact('item'));
  }

  public function create()
  {
    $categories = Category::all();
    $conditions = collect([
      (object)['id' => '1', 'content' => '良好'],
      (object)['id' => '2', 'content' => '目立った傷や汚れなし'],
      (object)['id' => '3', 'content' => 'やや傷や汚れあり'],
      (object)['id' => '4', 'content' => '状態が悪い'],
    ]);

    return view('item_sell', compact('categories', 'conditions'));
  }

  public function purchase($item_id)
  {
    $item = Item::findOrFail($item_id);
    return view(
      'item_purchase',
      compact('item')
    );
  }

  public function address($item_id)
  {
    $item = Item::findOrFail($item_id);
    return view('address_edit', compact('item'));
  }

  public function store(Request $request)
  {

    $request->validate([
      'name' => ['required'],
      'price' => ['required', 'numeric', 'min:0'],
      'description' => ['required', 'max:255'],
      'img_url' => ['required', 'image'],
      'category_ids' => ['required', 'array'],
      'condition' => ['required'],
    ]);

    $path = $request->file('img_url')->store('items', 'public');

    $item = Item::create([
      'user_id' => auth()->id(),
      'name' => $request->name,
      'price' => $request->price,
      'description' => $request->description,
      'condition' => $request->condition,
      'brand' => $request->brand,
      'img_url' => $path,
    ]);

    $item->categories()->sync($request->category_ids);
    return redirect()->route('item.index');
  }
}
