@extends('layouts.index')

@section('content')

<h1>マイブログ更新</h1>

<a href='/mypage/blogs/create'>ブログ新規作成</a>
<hr />

<form method="post">
@include('inc.error')

<div>タイトル: <input type="text" style="width: 400px" value="{{ data_get($data,'title')}}" /></div>
<div>本文: <textarea name="body" style="width: 600px;height: 200px;">{{ data_get($data,'body') }}</textarea></div>
<div>
公開する: 
<label>
    <input type="checkbox" name="status" value="1" {{ data_get($data,'status') ? 'checked': ''}}>
    公開する
</label>
</div>
<div>
<input type="submit" value="送信する">
</div>
</form>



@endsection