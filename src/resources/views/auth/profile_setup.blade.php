@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="l-form-container profile-setup">
  <h2 class="c-form-heading profile-setup__heading">プロフィール設定</h2>

  <div class="profile-setup__inner">
    <form class="profile-setup__form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="profile-setup__image-group">
        <div class="profile-setup__image-preview">
          @if($user->profile_url)
          <img src="{{ asset('storage/' . $user->profile_url) }}" alt="プロフィール画像" id="profile-img-preview" class="profile-setup__image">
          @else
          <div class="profile-setup__image-default"></div>
          @endif
        </div>
        <label class="c-btn-outline profile-setup__image-label">
          画像を選択する
          <input type="file" name="profile_url" id="profile_url" class="profile-setup__image-input" style="display:none">
        </label>
      </div>

      <div class="profile-setup__group profile-setup__group--username">
        <label class="c-form-label profile-setup__label" for="name">ユーザー名</label>
        <input class="c-form-input profile-setup__input" type="text" name="name" id="name" value="{{ old('name') }}">
        <p class="c-form-error">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="profile-setup__group">
        <label class="c-form-label profile-setup__label" for="postal_code">郵便番号</label>
        <input class="c-form-input profile-setup__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code')}}">
        <p class="c-form-error">
          @error('postal_code')
          {{ $message }}
          @enderror
      </div>

      <div class="profile-setup__group">
        <label class="c-form-label profile-setup__label" for="address">住所</label>
        <input class="c-form-input profile-setup__input" type="text" name="address" id="address" value="{{ old('address')}}">
        <p class="c-form-error">
          @error('address')
          {{ $message }}
          @enderror
      </div>

      <div class="profile-setup__group">
        <label class="c-form-label profile-setup__label" for="building">建物名</label>
        <input class="c-form-input profile-setup__input" type="text" name="building" id="building" value="{{ old('building')}}">
      </div>

      <div class="profile-setup__btn-wrap">
        <button class="c-btn-submit profile-setup__btn-submit" type="submit">更新する</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('profile_url').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      const preview = document.getElementById('profile-img-preview');
      if (preview) {
        preview.src = e.target.result;
      } else {
        const defaultBox = document.querySelector('.profile-setup__image-default');
        if (defaultBox) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.id = 'profile-img-preview';
          img.className = 'profile-setup__image';
          defaultBox.replaceWith(img);
        }
      }
    }

    if (file) {
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection