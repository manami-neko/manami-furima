@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')

<div class="sell-form__inner">
    <div class=sell-form__heading">
        <h2>商品の出品</h2>
    </div>

    <form class="form" action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品画像</span>
            </div>
            <div class="form__group-content">
                <input type="file" name="image">
            </div>
            <div class="form__error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>
        <p>商品の詳細</p>
        <div class="contact-form__group">
            <div class="form__group-title">
                <span class="form__label--item">カテゴリー</span>
            </div>
            <div class="checkbox">
                <div class="form__input--text">
                    @foreach ($categories as $category)
                    <label>
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) ? 'checked' : '' }}>
                        <span>{{ $category->content }}</span>

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
        <div class="sell-form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の状態</span>
            </div>
            <div class="group-content">
                <select name="condition_id">
                    <option value="" disabled {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>
                    @foreach ($conditions as $condition)
                    <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->content }}
                    </option>
                    @endforeach
                </select>
                <div class="form__error">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <P>商品名と説明</P>
        <div class="sell-form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品名</span>
            </div>
            <div class="group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name') }}">
                    </input>
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="sell-form__group">
            <div class="form__group-title">
                <span class="form__label--item">ブランド名</span>
            </div>
            <div class="group-content">
                <div class="form__input--text">
                    <input type="text" name="brand" value="{{ old('brand') }}">
                </div>
                <div class="form__error">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="contact-form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の説明</span>
            </div>
            <div class="group-content">
                <div class="form__input--textarea">
                    <input type="text" name="detail" value="{{ old('detail') }}"> </input>
                </div>
                <div class="form__error">
                    @error('detail')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell-form__group">
            <div class="form__group-title">
                <span class="form__label--item">販売価格</span>
            </div>
            <div class="group-content">
                <div class="form__input--text">
                    <div class="input-wrapper">
                        <span class="prefix">¥</span>
                        <input type="number" name="price" value="{{ old('price') }}">
                    </div>
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>

@endsection