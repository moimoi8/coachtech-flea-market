<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
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
    $tab = $request->query('page', 'sell');

    if ($tab === 'buy') {
      $items = $user->orderedItems;
    } else {
      $items = Item::where('user_id', $user->id)->get();
    }
    return view('mypage', compact('user', 'items', 'tab'));
  }

  public function edit()
  {
    $user = Auth::user();
    return view('auth.profile_setup', compact('user'));
  }

  public function update(ProfileRequest $request)
  {
    $user = Auth::user();

    $data = [
      'name' => $request->name,
      'postal_code' => $request->postal_code,
      'address' => $request->address,
      'building' => $request->building,
    ];

    if ($request->hasFile('profile_url')) {
      $path = $request->file('profile_url')->store('profiles', 'public');
      $data['profile_url'] = $path;
    }

    $user->fill($data)->save();

    return redirect()->route('item.index')->with('message', 'プロフィールを更新しました');
  }
}
