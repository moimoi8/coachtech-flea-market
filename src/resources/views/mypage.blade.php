@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="mypage">
  <div class="mypage__inner">

    <div class="mypage__user-info">
      <div class="mypage__user-group">
        <div class="profile-setup__image-preview">
          @if($user->profile_url)
          <img src="{{ asset('storage/' . $user->profile_url) }}" alt="プロフィール画像" class="profile-setup__image">
          @else
          <div class="profile-setup__image-default"></div>
          @endif
        </div>
        <h2 class="mypage__username">{{ Auth::user()->name }}</h2>
        <a href="/mypage/profile" class="mypage__image-label">プロフィールを編集</a>
      </div>
    </div>

    <nav class="mypage__tabs">
      <div class="mypage__tab-group">
        <a href="/mypage?page=sell" class="mypage__tab-item {{ request('page') != 'buy' ? 'mypage__tab-item--active' : '' }}">出品した商品
        </a>
        <a href="/mypage?page=buy" class="mypage__tab-item {{ request('page') == 'buy' ? 'mypage__tab-item--active' : '' }}">購入した商品
        </a>
      </div>
    </nav>

    <div class="mypage__grid">
      @foreach($items as $item)
      <article class="item-list__card">
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-list__card-link">
          <div class="item-list__image-wrap">
            <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-list__img">
          </div>
          <div class="item-list__card-info">
            <p class="item-list__item-name">{{ $item->name }}</p>
          </div>
        </a>
      </article>
      @endforeach
    </div>
  </div>
</div>
@endsection