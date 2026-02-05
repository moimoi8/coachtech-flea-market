@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="address-edit">
  <h2 class="address-edit__heading">住所の変更</h2>

  <div class="address-edit__inner">
    <form action="{{ route('address.update') }}" class="address-edit__form" method="POST">
      @csrf
      @method('PATCH')

      <div class="address-edit__group">
        <label class="address-edit__label" for="postal_code">郵便番号</label>
        <input class="address-edit__input" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        <p class="address-edit__error-message">
          @error('postal_code')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="address-edit__group">
        <label class="address-edit__label" for="address">住所</label>
        <input class="address-edit__input" type="text" name="address" id="address" value="{{ old('address') }}">
        <p class="address-edit__error-message">
          @error('address')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="address-edit__group">
        <label class="address-edit__label" for="building">建物名</label>
        <input class="address-edit__input" type="text" name="building" id="building" value="{{ old('building') }}">
        <p class="address-edit__error-message">
        </p>
      </div>

      <div class="address-edit__btn-wrap">
        <button class="c-button address-edit__btn-submit" type="submit">更新する</button>
      </div>
    </form>
  </div>
</div>
@endsection