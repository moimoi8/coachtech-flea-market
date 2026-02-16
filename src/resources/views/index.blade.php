@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="item-list">
  <div class="item-list__inner">

    <nav class="item-list__tabs">
      <div class="item-list__tab-group">
        <a href="{{ route('item.index', ['keyword' => $keyword ?? '']) }}" class="item-list__tab-item {{ $tab !== 'mylist' ? 'item-list__tab-item--active' : '' }}">おすすめ</a>
        <a href="{{ route('item.index', ['tab' => 'mylist', 'keyword' => $keyword ?? '']) }}" class="item-list__tab-item {{ $tab === 'mylist' ? 'item-list__tab-item--active' : '' }}">マイリスト</a>
      </div>
    </nav>

    <div class="item-list__grid">
      @foreach($items as $item)
      <article class="item-list__card">
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-list__card-link">
          <div class="item-list__image-wrap">
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="item-list__img">

            @if($item->is_sold)
            <span class="item-list__sold-label">SOLD</span>
            @endif
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