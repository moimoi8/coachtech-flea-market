@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="item-purchase">
  <div class="item-purchase__inner">
    <div class="item-purchase__main">

      <div class="item-purchase__item-section">
        <div class="item-purchase__item-image">
          <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
        </div>
        <div class="item-purchase__item-detail">
          <h1 class="item-purchase__item-name">{{ $item->name }}</h1>
          <p class="item-purchase__item-price">&yen;{{ number_format($item->price) }}</p>
        </div>
      </div>

      <div class="item-purchase__section">
        <div class="item-purchase__section-header">
          <h2 class="item-purchase__label">支払い方法</h2>
        </div>
        <div class="c-select-wrap item-purchase__select-wrap">
          <select name="payment_method" class="item-purchase__select">
            <option value="" selected disabled>選択してください</option>
            <option value="convenience">コンビニ払い</option>
            <option value="card">カード払い</option>
          </select>
        </div>
      </div>

      <div class="item-purchase__section">
        <div class="item-purchase__section-header">
          <h2 class="item-purchase__label">配送先</h2>
          <a href="{{ route('address.edit') }}" class="item-purchase__edit-link">変更する</a>
        </div>
        <div class="item-purchase__selected-content">
          <p>&Tilde; {{ $user->postal_code }}</p>
          <p>{{ $user->address }}{{ $user->building }}</p>
        </div>
      </div>
    </div>

    <aside class="item-purchase__summary">
      <div class="item-purchase__summary-board">
        <div class="item-purchase__summary-row">
          <span class="item-purchase__summary-label">商品代金</span>
          <span class="item-purchase__summary-value">&yen;{{ number_format($item->price) }}</span>
        </div>
        <div class="item-purchase__summary-row">
          <span class="item-purchase__summary-label">支払い方法</span>
          <span class="item-purchase__summary-value">コンビニ払い</span>
        </div>
      </div>

      <form action="{{ route('purchase.store', $item->id) }}" method="POST">
        @csrf
        <button type="submit" class="c-btn-submit item-purchase__btn-submit">購入する</button>
      </form>
    </aside>
  </div>
</div>
@endsection