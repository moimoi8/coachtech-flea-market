@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
@endsection

@section('content')
<div class="l-form-container item-sell">
  <div class="item-sell__inner">
    <h1 class="c-form-heading item-sell__title">商品の出品</h1>

    @if ($errors->any())
    <div style="color: red; margin-bottom: 20px;">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="item-sell__form">
      @csrf

      <div class="item-sell__section">
        <h2 class="item-sell__section-label">商品画像</h2>
        <div class="item-sell__image-upload">
          <div class="item-sell__image-preview">
            <img id="preview" src="" alt="プレビュー" style="display: none;">
          </div>
          <label class="item-sell__image-label">
            <input type="file" name="img_url" id="img_url" class="item-sell__file-input">
            <span class="c-btn-outline">画像を選択する</span>
          </label>
        </div>
      </div>

      <div class="item-sell__section">
        <h3 class="item-sell__sub-title">商品の説明</h3>


        <div class="item-sell__field">
          <label class="item-sell__label">カテゴリー</label>
          <div class="item-sell__category-group">
            @foreach($categories as $category)
            <label class="item-sell__category-item">
              <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="item-sell__category-input"
                {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
              <span class="c-tag">{{ $category->content }}</span>
            </label>
            @endforeach
          </div>
        </div>

        <div class="item-sell__field">
          <label for="condition" class="item-sell__label">商品の状態</label>
          <div class="c-select-wrap">
            <select name="condition" id="condition" class="item-sell__select">
              <option value="" selected disabled>選択してください</option>
              @foreach($conditions as $condition)
              <option value="{{ $condition }}" {{ old('condition') == $condition ? 'selected' : '' }}>
                {{ $condition }}
              </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="item-sell__section">
        <h3 class="item-sell__sub-title">商品名と説明</h3>

        <div class="item-sell__field">
          <label for="name" class="item-sell__label">商品名</label>
          <input type="text" name="name" id="name" class="item-sell__input" value="{{ old('name') }}">
        </div>

        <div class="item-sell__field">
          <label for="brand" class="item-sell__label">ブランド名</label>
          <input type="text" name="brand" id="brand" class="item-sell__input" value="{{ old('brand') }}">
        </div>

        <div class="item-sell__field">
          <label for="description" class="item-sell__label">商品の説明</label>
          <textarea name="description" id="description" class="item-sell__textarea" rows="5">{{ old('description') }}</textarea>
        </div>
      </div>

      <div class="item-sell__section">
        <h3 class="item-sell__sub-title">販売価格</h3>
        <div class="item-sell__field">
          <label for="price" class="item-sell__label">販売価格</label>
          <div class="item-sell__price-input-wrap">
            <span class="item-sell__price-unit">&yen;</span>
            <input type="number" name="price" id="price" class="item-sell__price-input" value="{{ old('price') }}">
          </div>
        </div>
      </div>

      <button type="submit" class="c-btn-submit item-sell__btn-submit">出品する</button>
    </form>
  </div>
</div>

<script>
  document.getElementById('img_url').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewImg = document.getElementById('preview');

    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        if (previewImg) {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        }
      }
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection