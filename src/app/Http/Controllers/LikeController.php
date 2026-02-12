<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
  public function store($item_id)
  {
    $user = auth()->user();
    $like = $user->likeItems()->where('item_id', $item_id)->first();
    if ($like) {
      $user->likeItems()->detach($item_id);
    } else {
      $user->likeItems()->attach($item_id);
    }
    return back();
  }
}
