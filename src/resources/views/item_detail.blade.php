@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="item-detail">
  <div class="item-detail__inner">
    <div class="item-detail__image">
      <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="item-detail__img">
    </div>

    <div class="item-detail__info">
      <h1 class="item-detail__name">
        {{ $item->name }}
      </h1>

      @if($item->brand && $item->brand !== 'null')
      <p class="item-detail__brand">
        {{ $item->brand }}
      </p>
      @endif

      <p class="item-detail__price">
        &yen;{{ number_format($item->price) }}<span class="item-detail__tax">(税込)</span>
      </p>

      <div class="item-detail__stats">
        <div class="item-detail__stat item-detail__stat--like">
          <img src="{{ asset('images/heart-default.png') }}" alt="いいね" class="item-detail__icon">
          <div class="item-detail__count">{{ $item->likes->count() }}</div>
        </div>
        <div class="item-detail__stat item-detail__stat--comment">
          <img src="{{ asset('images/comment-logo.png') }}" alt="コメント" class="item-detail__icon">
          <div class="item-detail__count">{{ $item->comments->count() }}</div>
        </div>
      </div>
      <a href="..." class="c-button item-detail__buy-button">購入手続きへ</a>

      <div class="item-detail__section">
        <h2 class="item-detail__section-title">商品説明</h2>
        <p class="item-detail__description">{{ $item->description }}</p>
      </div>

      <div class="item-detail__section">
        <h2 class="item-detail__section-title">商品の情報</h2>
        <table class="item-detail__table">
          <tr class="item-detail__table-row">
            <th class="item-detail__table-label">カテゴリー</th>
            <td class="item-detail__table-data">
              @foreach($item->categories as $category)
              <span class="c-tag">{{ $category->content }}</span>
              @endforeach
            </td>
          </tr>
          <tr class="item-detail__table-row">
            <th class="item-detail__table-label">商品の状態</th>
            <td class="item-detail__table-data">{{ $item->condition }}</td>
          </tr>
        </table>
      </div>

      <div class="item-detail__section">
        <h2 class="item-detail__section-title item-detail__section-title--gray">コメント({{ $item->comments->count() }})</h2>
        @foreach($item->comments as $comment)
        <div class="item-detail__comment">
          <div class="item-detail__comment-user">
            <div class="item-detail__comment-avatar"></div>
            <strong class="item-detail__comment-name">{{ $comment->user->name }}</strong>
          </div>
          <p class="item-detail__comment-text">{{ $comment->content }}</p>
        </div>
        @endforeach
      </div>

      <form action="{{ route('comment.store', $item->id) }}" method="POST" class="item-detail__form">
        @csrf
        <label for="comment" class="item-detail__form-label">商品へのコメント</label>
        <textarea name="comment" id="comment" class="item-detail__form-textarea" rows="5"></textarea>
        <button type="submit" class="c-button item-detail__form-submit">コメントを送信する</button>
      </form>
    </div>
  </div>
</div>
@endsection