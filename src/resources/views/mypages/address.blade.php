@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypages/address.css') }}">
@endsection

@section('content')

<div class="address-form__content">
    <div class="address-form__heading">
        <h2>住所の変更</h2>
    </div>

    <form class="form" action="/purchase/address/{{ $item->id }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- <input type="hidden" name="name" value="{{ $mypage->name }}"> -->

        <div class="form__group">
            <div class="form__group-title">
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">郵便番号</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="postal_code" value="{{ $mypage->postal_code }}">
                        </div>
                        <div class="form__error">
                            @error('postal_code')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">住所</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="address" value="{{ $mypage->address }}">
                        </div>
                        <div class="form__error">
                            @error('address')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">建物名</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="building" value="{{ $mypage->building }}">
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">更新する</button>
                </div>
    </form>
</div>


@endsection