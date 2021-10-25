@extends('layouts.index')

@section('content')

<h1>マイブログ新規登録</h1>

<a href='/mypage/blogs/create'>ブログ新規作成</a>
<hr />

<form method="post">
@include('inc.error')

<div>タイトル: <input type="text" style="width: 400px" value="{{ old('title')}}" /></div>
<div>本文: <textarea name="body" style="width: 600px;height: 200px;">{{ old('body') }}</textarea></div>
<div>
公開する: 
<label>
    <input type="checkbox" name="status" value="1" {{ old('status') ? 'checked': ''}}>
    公開する
</label>
</div>
<div>
<input type="submit" value="送信する">
</div>
</form>



@endsection