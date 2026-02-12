@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

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
          <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
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
          <select class="item-purchase__select" id="payment-select">
            <option value="" disabled {{ old('payment_method') === null ? 'selected' : '' }}>選択してください</option>
            <option value="convenience" {{ old('payment_method') === 'convenience' ? 'selected' : '' }}>コンビニ払い</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>カード払い</option>
          </select>
        </div>
      </div>

      <div class="item-purchase__section">
        <div class="item-purchase__section-header">
          <h2 class="item-purchase__label">配送先</h2>
          <a href="{{ route('item.address.edit', ['item_id' => $item->id]) }}" class="item-purchase__edit-link">変更する</a>
        </div>
        <div class="item-purchase__selected-content">
          <p>〒 {{ $user->postal_code }}</p>
          <p>{{ $user->address }}{{ $user->building }}</p>
        </div>
      </div>
    </div>

    <aside class="item-purchase__summary">
      @if ($errors->any())
      <div style="background: #ffdada; padding: 10px; margin-bottom: 10px;">
        <ul>
          @foreach ($errors->all() as $error)
          <li class="c-form-error">{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <div
        class="item-purchase__summary-board">
        <div class="item-purchase__summary-row">
          <span class="item-purchase__summary-label">商品代金</span>
          <span class="item-purchase__summary-value">&yen;{{ number_format($item->price) }}</span>
        </div>
        <div class="item-purchase__summary-row">
          <span class="item-purchase__summary-label">支払い方法</span>
          <span class="item-purchase__summary-value" id="summary-payment">未選択</span>
        </div>
      </div>

      @error('payment_method')
      <p class="c-form-error">{{ $message }}</p>
      @enderror
      @error('postal_code')
      <p class="c-form-error">{{ $message }}</p>
      @enderror

      <form id="purchase-form" action="{{ route('item.purchase', ['item_id' => $item->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="payment_method" id="hidden-payment-method" value="{{ old('payment_method') }}">
        <input type="hidden" name="postal_code" value="{{ $user->postal_code }}">
        <input type="hidden" name="address" value="{{ $user->address }}">
        <input type="hidden" name="building" value="{{ $user->building }}">

        <button type="submit" class="c-btn-submit item-purchase__btn-submit">購入する</button>
      </form>
    </aside>
  </div>
</div>
<script>
  const paymentSelect = document.getElementById('payment-select');
  const summaryPayment = document.getElementById('summary-payment');
  const hiddenPayment = document.getElementById('hidden-payment-method');

  function updateSummary() {
    const selectedValue = paymentSelect.value;
    const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
    if (paymentSelect.value) {
      summaryPayment.textContent = selectedText;
      hiddenPayment.value = selectedValue;
    } else {
      summaryPayment.textContent = '未選択';
      hiddenPayment.value = '';
    }
  }

  paymentSelect.addEventListener('change', updateSummary);

  document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endsection