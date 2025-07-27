@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="show-form__inner">
    <div class="show-form__group">
        <div class="form__group-title">
            <span class="form__label--item">商品画像</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <img src="{{  asset($item->image) }}">
            </div>
        </div>
    </div>
</div>
<div class="show-form__group">
    <div class="form__group-title">
        <span class="form__label--item">商品名</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--text">
            <input type="text" name="name" value="{{ $item->name }}">
            </input>
        </div>
    </div>
</div>
<div class="show-form__group">
    <div class="form__group-title">
        <span class="form__label--item">ブランド名</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--text">
            <input type="text" name="brand" value="{{ $item->brand }}">
            </input>
        </div>
    </div>
</div>

<div class="favorite-content">
    <img src="{{  asset('storage/images/星アイコン8.png') }}" class="small-img">
</div>


<div class="comment-content">
    <img src="{{  asset('storage/images/ふきだしのアイコン.png') }}" class="small-img">
</div>


<label class="purchase-button">
    <a href="/purchase/{{ $item->id }}" class="sell">購入手続きへ</a>
</label>
<div class="show-form__group">
    <div class="form__group-title">
        <span class="form__label--item">値段</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--text">
            <input type="number" name="price" value="{{ $item->price }}"></input>
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
    </div>
</div>
<div class="show-form__group">
    <div class="form__group-title">
        <span class="form__label--item">商品の状態</span>
    </div>
    <div class="form__group-content">
        <div class="form__input--textarea">
            <input type="text" name="condition" value="{{ $item->condition->content }}"> </input>
        </div>
    </div>
</div>
<form action="/items/show" method="post">
    @csrf

    <div class="show-form__group">
        <div class="form__group-title">
            <span class="form__label--item">商品へのコメント</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--textarea">
                <input type="text" name="comment">
            </div>
        </div>
    </div>

</form>
@endsection