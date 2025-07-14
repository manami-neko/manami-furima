@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="register-form__inner">
    <!-- <form class="form" action="/edit" method="post" enctype="multipart/form-data">
        @csrf -->
    <div class="register-form__group">
        <div class="form__group-title">
            <span class="form__label--item">商品画像</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <img src="{{  asset($item->image) }}">
            </div>
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
    </div>
</div>
<div class="register-form__group">
    <div class="form__group-title">
        <span class="form__label--item">商品名</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--text">
            <input type="text" name="name" value="{{ $item->name }}">
            </input>
        </div>
        <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>
    </div>
</div>
<div class="register-form__group">
    <div class="form__group-title">
        <span class="form__label--item">値段</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--text">
            <input type="number" name="price" value="{{ $item->price }}"></input>
        </div>
        <div class="form__error">
            @error('price')
            {{ $message }}
            @enderror
        </div>
    </div>
</div>
<div class="contact-form__group">
    <div class="form__group-title">
        <span class="form__label--item">カテゴリー</span>
    </div>
    <div class="checkbox">
        <div class="form__input--text">
            @foreach ($categories as $category)
            <label>
                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ $item->categories->contains($category->id) ? 'checked' : '' }}>{{ $category->content }}
            </label>
            @endforeach
        </div>
        <div class="form__error">
            @error('category_ids')
            {{ $message }}
            @enderror
        </div>
    </div>
</div>
<div class="contact-form__group">
    <div class="form__group-title">
        <span class="form__label--item">商品説明</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--textarea">
            <input type="text" name="detail" value="{{ $item->detail }}"> </input>
        </div>
        <div class="form__error">
            @error('detail')
            {{ $message }}
            @enderror
        </div>
    </div>
</div>


</form>

</div>
</div>

@endsection