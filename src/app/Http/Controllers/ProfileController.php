<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;

class ProfileController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();

    if (!$user) {
      return redirect()->route('login');
    }
    $tab = $request->query('tab', 'sell');

    if ($tab === 'buy') {
      $items = $user->boughtItems;
    } else {
      $items = Item::where('user_id', $user->id)->get();
    }
    return view('mypage', compact('user', 'items', 'tab'));
  }

  public function edit()
  {
    $user = Auth::user();
    return view('profile_setup', compact('user'));
  }

  public function update(Request $request)
  {
    $request->validate([
      'name' => ['required'],
      'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
      'address' => ['required'],
      'building' => ['nullable'],
      'img_url' => ['nullable', 'image'],
    ]);

    $user = Auth::user();

    if ($request->hasFile('img_url')) {
      $path = $request->file('img_url')->store('profiles', 'public');
      $user->img_url = $path;
    }

    $user->update([
      'name' => $request->name,
      'postal_code' => $request->postal_code,
      'address' => $request->address,
      'building' => $request->building,
    ]);
    return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
  }
}
