@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="show-form__inner">
    <div class="show-form__group">
        <div class="form__group-content">
            <div class="form__item">
                <img src="{{  asset($item->image) }}">
            </div>
        </div>
    </div>
    <div class="item-info">
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
            <form action="{{ url('/items/' . $item->id) }}" method="post" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; font-size: 24px; cursor: pointer;">
                    @if ($isLiked)
                    <span style="color: gold;">★</span> {{-- いいね済み：塗りつぶし --}}
                    @else
                    <span style="color: gray;">☆</span> {{-- 未いいね：空星 --}}
                    @endif
                </button>
                <span>{{ $item->likes->count() }}</span>
            </form>
        </div>



        <!-- <div class="favorite-content">
            <img src="{{  asset('storage/images/星アイコン8.png') }}" class="small-img">
        </div> -->


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
        <div class="show-form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品説明</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--textarea">
                    <input type="text" name="detail" value="{{ $item->detail }}"> </input>
                </div>
            </div>
        </div>
        <div class="show-form__group">
            <div class="form__group-title">
                <span class="form__label--item">カテゴリー</span>
            </div>
            <div class="checkbox">
                <div class="form__input--text">
                    @foreach ($item->categories as $category)
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
                    <div class="form__button">
                        <button class="form__button-submit" type="submit">コメントを送信する</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection