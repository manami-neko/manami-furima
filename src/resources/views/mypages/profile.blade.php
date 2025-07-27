@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypages/profile.css') }}">
@endsection

@section('content')

<div class="form__mypage">

    <div class="mypage-content">
        <img src="{{  asset('storage/' . $mypage->image) }}" class="small-img">
        </a>
        <div>
            {{ $user->name }}
        </div>
        <label class="profile-button">
            <a href="/mypage/profile" class="edit">プロフィールを編集</a>
        </label>
    </div>
</div>

<div class="nav__inner">
    <a href="items/sell" class="sell">出品した商品</a>
    <a href="items/purchase" class="buy">購入した商品</a>
</div>

<div class="form__item">
    @foreach($items as $item)
    <div class="item-content">
        <a href="{{ url('/items/' . $item->id) }}">
            <img src="{{  asset($item->image) }}" class="small-img">
        </a>
        <div>{{ $item->name }}
        </div>
    </div>
    @endforeach

    @endsection