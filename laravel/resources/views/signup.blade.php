@extends('layouts.index')

@section('content')

<h1>ユーザー登録</h1>

<form method="post">
    @csrf
    @include('inc.error')

    名前: <input type="text" name="name" value="{{ old('name') }}"/>
    メルアド: <input type="text" name="email" value="{{ old('email') }}"/>
    パスワード: <input type="password" name="password"/>

    <input type="submit" value="送信する" />
</form> 

@endsection