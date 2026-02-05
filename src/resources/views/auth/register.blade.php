@extends('layouts.app')

@section('content')
<div class="register-form">
  <h2 class="register-form__heading">会員登録</h2>

  <div class="register-form__inner">
    <form action="/register" class="register-form__form" method="POST">
      @csrf

      <div class="register-form__group">
        <label class="register-form__label" for="name">ユーザー名</label>
        <input class="register-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
        <p class="register-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="register-form__group">
        <label class="register-form__label" for="email">メールアドレス</label>
        <input class="register-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
        <p class="register-form__error-message">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="register-form__group">
        <label class="register-form__label" for="password">パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password">
        <p class="register-form__error-message">
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="register-form__group">
        <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
        <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
      </div>

      <div class="register-form__btn-wrap">
        <button class="c-button c-button--primary register-form__btn-submit" type="submit">登録する</button>
      </div>
    </form>

    <div class="register-form__link">
      <a href="/login">ログインはこちら</a>
    </div>
  </div>
</div>
@endsection