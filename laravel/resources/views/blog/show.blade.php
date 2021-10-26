@extends('layouts.index')

@section('content')

@if(today()->is('12-15'))
<h1>メリークリスマス</h1>
@endif

<h1>{{ $blog->title }} {{ $random }}</h1>

<div>{{ nl2br($blog->body) }}</div>

<p>書き手： {{ $blog->user->name }}</p>

<h2>コメント</h2>
@foreach($blog->comments()->oldest()->get() as $comment)
    <hr/>
    <p>{{ $comment->name }} ({{ $comment->created_at }})</p>
    <p>{!! nl2br($comment->body) !!}</p>
@endforeach


@endsection