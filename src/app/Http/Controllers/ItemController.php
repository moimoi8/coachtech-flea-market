<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    $tab = $request->query('tab', 'recommend');
    $keyword = $request->query('keyword');
    $query = Item::query()->with('categories');

    if ($keyword) {
      $query->where('name', 'like', '%' . $keyword . '%');
    }

    if ($tab === 'mylist') {
      $items = auth()->check()
        ? auth()->user()->likeItems()->whereIn('items.id', $query->pluck('id'))->get()
        : collect();
    } else {
      $items = $query->get();
    }
    return view('index', compact('items', 'tab', 'keyword'));
  }

  public function show($item_id)
  {
    $item = Item::with(['categories', 'comments.user'])->withCount('likes')->findOrFail($item_id);

    $is_liked = false;
    if (auth()->check()) {
      $is_liked = auth()->user()->likeItems()->where('item_id', $item_id)->exists();
    }

    return view('item_detail', compact('item', 'is_liked'));
  }

  public function create()
  {
    $categories = Category::all();
    $conditions = [
      '良好',
      '目立った傷や汚れなし',
      'やや傷や汚れあり',
      '状態が悪い',
    ];

    return view('item_sell', compact('categories', 'conditions'));
  }

  public function purchase($item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = auth()->user();

    return view('item_purchase', compact('item', 'user'));
  }

  public function address($item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = auth()->user();

    return view('address_edit', compact('item', 'user'));
  }

  public function addressUpdate(AddressRequest $request, $item_id)
  {
    $user = auth()->user();
    $user->update([
      'postal_code' => $request->postal_code,
      'address' => $request->address,
      'building' => $request->building,
    ]);

    return redirect()->route('item.purchase', ['item_id' => $item_id])
      ->with('message', '配送先を変更しました');
  }

  public function purchaseStore(PurchaseRequest $request, $item_id)
  {
    $user = auth()->user();
    $item = Item::findOrFail($item_id);

    if ($item->is_sold) {
      return back()->with('error', 'この商品はすでに売り切れています');
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = Session::create([
      'payment_method_types' => ['card', 'konbini'],
      'line_items' => [[
        'price_data' => [
          'currency' => 'jpy',
          'product_data' => [
            'name' => $item->name,
          ],
          'unit_amount' => $item->price,
        ],
        'quantity' => 1,
      ]],
      'mode' => 'payment',
      'success_url' => route('item.purchase.success', ['item_id' => $item->id]),
      'cancel_url' => route('item.show', ['item_id' => $item->id]),
    ]);

    return redirect($session->url, 303);
  }

  public function purchaseSuccess(Request $request, $item_id)
  {
    $user = auth()->user();
    $item = Item::findOrFail($item_id);

    if ($item->is_sold) {
      return redirect()->route('item.index');
    }

    DB::transaction(function () use ($user, $item) {
      Order::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'stripe_checkout_id' => 'completed',
        'payment_method' => 'stripe',
        'postal_code' => $user->postal_code ?? '000-0000',
        'address' => $user->address ?? '未設定',
      ]);

      $item->update(['is_sold' => true]);
    });

    return redirect()->route('item.index')->with('message', '購入が完了しました！');
  }

  public function store(ExhibitionRequest $request)
  {
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
