@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<div class="nav__inner">
    <a href="/?tab=recommend" class="nav {{ request('tab', 'recommend') === 'recommend' ? 'active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist" class="mylist__nav {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<!-- <div class="nav__inner">
    <a href="/" class="nav">おすすめ</a>
    <a href="/" class="mylist__nav">マイリスト</a>
</div> -->

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
</div>


@endsection