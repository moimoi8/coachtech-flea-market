@extends('layouts.app')

@section('content')
<div class="profile-setup">
  <h2 class="profile-setup__heading">プロフィール設定</h2>

  <div class="profile-setup__inner">
    <form class="profile-setup__form" action="/mypage/profile" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="profile-setup__image-group">
        <div class="profile-setup__image-preview"></div>
        <label class="profile-setup__image-label">
          画像を選択する
          <input type="file" name="image" class="profile-setup__image-input" style="display:none">
        </label>
      </div>

      <div class="profile-setup__group profile-setup__group--username">
        <label class="profile-setup__label" for="name">ユーザー名</label>
        <input class="profile-setup__input" type="text" name="name" id="name" value="{{ old('name') }}">
        <p class="profile-setup__error">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="profile-setup__group">
        <label class="profile-setup__label" for="postal_code">郵便番号</label>
        <input class="profile-setup__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code')}}">
        <p class="profile-setup__error">
          @error('postal_code')
          {{ $message }}
          @enderror
      </div>

      <div class="profile-setup__group">
        <label class="profile-setup__label" for="address">住所</label>
        <input class="profile-setup__input" type="text" name="address" id="address" value="{{ old('address')}}">
        <p class="profile-setup__error">
          @error('address')
          {{ $message }}
          @enderror
      </div>

      <div class="profile-setup__group">
        <label class="profile-setup__label" for="building">建物名</label>
        <input class="profile-setup__input" type="text" name="building" id="building" value="{{ old('building')}}">
      </div>

      <div class="profile-setup__btn-wrap">
        <button class="c-button profile-setup__btn-submit" type="submit">更新する</button>
      </div>
    </form>
  </div>
</div>
@endsection