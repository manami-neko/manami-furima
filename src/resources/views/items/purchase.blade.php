@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
@endsection

@section('content')

<form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="post">
    @csrf


    <div class="purchase-form__inner">
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
    <div class="purchase-form__group">
        <div class="form__group-title">
            <span class="form__label--item">商品名</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="name" value="{{ $item->name }}" readonly>
            </div>
        </div>
    </div>
    <div class="purchase-form__group">
        <div class="form__group-title">
            <span class="form__label--item">値段</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="number" name="price" value="{{ $item->price }}" readonly>
            </div>
        </div>
    </div>
    <div class="payment-form__group">
        <div class="form__group-title">
            <span class="form__label--item">支払方法</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                <select class="payment" name="payment">
                    <option value="">選択してください</option>
                    <option value="convenience_store">コンビニ支払い</option>
                    <option value="card">カード支払い</option>
                </select>
            </div>
        </div>
    </div>
    <div class="purchase-form__group">
        <div class="form__group-title">
            <span class="form__label--item">配送先</span>
        </div>

        <label class="address-button">
            <a href="/purchase/address/{{ $item->id }}" class="address">変更する</a>
        </label>
        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="postal_code" value="{{ $mypage->postal_code }}" readonly>
            </div>
        </div>

        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="address" value="{{ $mypage->address }}" readonly>
            </div>
        </div>

        <div class="form__group-content">
            <div class="form__input--text">
                <input type="text" name="building" value="{{ $mypage->building }}" readonly>
            </div>
        </div>
        <button type="submit">購入する</button>
</form>
@endsection