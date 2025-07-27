@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypages/edit.css') }}">
@endsection

@section('content')

<div class="edit-form__content">
    <div class="edit-form__heading">
        <h2>プロフィール設定</h2>
    </div>

    <form class="form" action="/mypage/profile" method="post" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="form__mypage">
            <div class="mypage-content">
                <img src="{{  asset('storage/' . $mypage->image) }}" class="small-img">
                </a>
                <label class="image-upload-button">
                    画像を選択する
                    <input type="file" name="image" accept="image/*" hidden>
                </label>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name', $user->name ) }}" />
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postal_code" value="{{ old('postal_code', $mypage->postal_code ) }}" />
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
                    <input type="text" name="address" value="{{ old('address', $mypage->address ) }}" />
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
                    <input type="text" name="building" value="{{ old('building', $mypage->building ) }}" />
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>

    </form>
</div>

@endsection