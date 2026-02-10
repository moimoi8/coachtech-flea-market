@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="l-form-container login-form">
  <h2 class="c-form-heading login-form__heading">ログイン</h2>

  <div class="login-form__inner">
    <form action="/login" class="login-form__form" method="POST">
      @csrf

      <div class="login-form__group">
        <label class="c-form-label login-form__label" for="email">メールアドレス</label>
        <input class="c-form-input login-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
        <p class="c-form-error">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="login-form__group">
        <label class="c-form-label login-form__label" for="password">パスワード</label>
        <input class="c-form-input login-form__input" type="password" name="password" id="password" value="{{ old('password') }}">
        <p class="c-form-error">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>

      <div class="login-form__btn-wrap">
        <button class="c-btn-submit login-form__btn-submit" type="submit">ログインする</button>
      </div>
    </form>

    <div class="login-form__link">
      <a href="/register">会員登録はこちら</a>
    </div>
  </div>
</div>
@endsection