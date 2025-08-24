@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<div class="nav__inner">
    <a href="{{ route('items.index', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}"
        class="nav {{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
        class="mylist__nav {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="form__item">
    @foreach($items as $item)
    <div class="item-content" style="position: relative;">
        <a href="{{ url('/items/' . $item->id) }}">

            <img src="{{ asset('storage/' . $item->image) }}" class="small-img">

            @if($item->status === 'sold')
            <span class="sold-label">SOLD</span>
            @endif

        </a>
        <div>{{ $item->name }}
        </div>
    </div>
    @endforeach
</div>

@endsection