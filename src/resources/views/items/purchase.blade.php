@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
@endsection

@section('content')

<form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="post">
    @csrf


    <div class="purchase-form__inner">
        <div class="purchase-form__group">
            <div class="form__group-title">
                <div class="form__group-content">
                    <div class="form__input--text">
                        <img src="{{  asset($item->image) }}" class="item-image">


                    </div>
                </div>
            </div>
        </div>
        <div class="purchase-form__group">
            <div class="form__group-title">
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ $item->name }}" readonly>
                </div>
            </div>
        </div>
        <div class="purchase-form__group">
            <div class="form__group-title">
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    ￥<input type="number" name="price" value="{{ $item->price }}" readonly>
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
                    <div class="form__error">
                        @error('payment')
                        {{ $message }}
                        @enderror
                    </div>
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
                    〒<input type="text" name="postal_code" value="{{  session('purchase_address.postal_code', $mypage->postal_code) }}" readonly>
                </div>
                <div class="form__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ session('purchase_address.address', $mypage->address) }}" readonly>
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ session('purchase_address.building', $mypage->building) }}" readonly>
                </div>
                <div class="form__error">
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="purchase-form__group">
        <div class="form__group-title">
            <span class="form__label--item">商品代金</span>
        </div>
        <div class="form__group-content">
            <div class="form__input--text">
                ￥<input type="number" name="price" value="{{ $item->price }}" readonly>
            </div>
        </div>
    </div>
    <div class="payment-form__group">
        <div class="form__group-title">
            <span class="form__label--item">支払方法</span>
        </div>
    </div>
    <div class="purchase-section">

        @if($sold)
        <button class="purchase-button" disabled>購入不可</button>
        @else
        <button class="purchase-button">購入する</button>
        @endif
    </div>

</form>
@endsection