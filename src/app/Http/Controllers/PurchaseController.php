<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
  public function store(PurchaseRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);

    Order::create([
      'user_id' => Auth::id(),
      'item_id' => $item->id,
      'payment_method' => $request->payment_method,
    ]);

    $item->update(['is_sold' => true]);

    return redirect()->route('item.index')->with('message', '購入が完了しました');
  }
}
