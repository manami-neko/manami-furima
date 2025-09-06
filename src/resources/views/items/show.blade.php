@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="show-form__inner">
    <div class="show-form__group">
        <div class="form__group-content">
            <div class="form__item">
                <img src="{{ asset('storage/' . $item->image) }}">
            </div>
        </div>
    </div>
    <div class="item-info">
        <div class="show-form__group">
            <div class="form__group-title">
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ $item->name }}" readonly>
                    </input>
                </div>
            </div>
        </div>
        <div class="show-form__group">
            <div class="form__group-title">
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="brand" value="{{ $item->brand }}" readonly>
                    </input>
                </div>
            </div>
        </div>
        <div class="show-form__group">
            <div class="form__group-content">
                <div class="form__input--text">
                    <span class="currency">￥</span>
                    <span name="price">{{ number_format($item->price) }}</span>
                    <span class="tax">(税込)</span>
                </div>
            </div>
        </div>
        <div class="icon-content">
            <div class="favorite-content">
                <form action="{{ url('/items/' . $item->id . '/like') }}" method="post" class="like" novalidate>
                    @csrf
                    <button type="submit" class="like-button">
                        @if ($isLiked)
                        <span class="liked">★</span> {{-- いいね済み：塗りつぶし --}}
                        @else
                        <span class="not-liked">☆</span> {{-- 未いいね：空星 --}}
                        @endif
                    </button>
                    <span>{{ $item->likes->count() }}</span>
                </form>
            </div>
            <div class="comment-content">
                <img src="{{  asset('storage/images/ふきだしのアイコン.png') }}" class="small-img">
                <span>{{ $item->comments->count() }}</span>
            </div>

        </div>
        <div class="purchase-button">
            <a href="/purchase/{{ $item->id }}" class="sell">購入手続きへ</a>
        </div>
        <div class="show-form__group">
            <span class="form__label">商品説明</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--textarea">
                <input type="text" name="detail" value="{{ $item->detail }}" readonly>
            </div>
        </div>
        <span class="form__label">商品の情報</span>
        <div class="show-form__group">
            <div class="form__group-title">
                <span class="form__label--item">カテゴリー</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    @foreach ($item->categories as $category)
                    <label>
                        <input type="hidden" name="category_ids[]" value="{{ $category->id }}" {{ $item->categories->contains($category->id) ? 'checked' : '' }}>{{ $category->content }}
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
                <div class="form__input--text">
                    <input type="text" name="condition" value="{{ $item->condition->content }}" readonly>
                </div>
            </div>
        </div>
        <div>
            <h3>コメント({{ $item->comments->count() }})</h3>

            @foreach ($item->comments as $comment)
            <div class="comment-name">

                <img src="{{ asset('storage/' . $comment->user->mypage->image) }}" class="comment-icon">

                <strong>{{ $comment->user->name }}</strong>
            </div>
            <p>{{ $comment->content }}</p>

            @endforeach
        </div>
        <form action="/items/{{ $item->id }}/comment" method="post" novalidate>
            @csrf
            <div class="form__group-title">
                <span class="form__label--comment">商品へのコメント</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--textarea">
                    <textarea name="content" cols="80" rows="15"></textarea>
                </div>
                <div class="form__error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">コメントを送信する</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection