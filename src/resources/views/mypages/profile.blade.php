@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypages/profile.css') }}">

@endsection

@section('content')

<div class="mypage-content">
    <img src="{{  asset('storage/' . $mypage->image) }}" class="profile-icon">
    <div class="user-name">
        {{ $user->name }}
    </div>
    <label class="profile-button">
        <a href="/mypage/profile" class="edit">プロフィールを編集</a>
    </label>
</div>

<div class="nav__inner">
    <div class="nav__inner">
        <a href="/mypage?tab=sell" class="sell {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?tab=buy" class="buy {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>
</div>

<div class="form__item">
    @foreach($items as $item)
    <div class="item-content">
        <a href="{{ url('/items/' . $item->id) }}">
            <img src="{{ asset('storage/' . $item->image) }}" class="small-img">
        </a>
        <div class="item-name">
            {{ $item->name }}
        </div>
    </div>
    @endforeach

    @endsection