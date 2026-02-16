<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
  public function store($item_id)
  {
    $user = auth()->user();

    $user->likeItems()->toggle($item_id);

    return back();
  }
}
