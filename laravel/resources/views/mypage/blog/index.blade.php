@extends('layouts.index')

@section('content')

<h1>マイブログ一覧</h1>

<a href='/mypage/blogs/create'>ブログ新規作成</a>
<hr />


<table>
    <tr>
        <th>ブログ名</th>
    </tr>
    @foreach($blogs as $blog)
        <td><a href='{{ route('mypage.blog.edit',$blog->id) }}'>{{ $blog->title }}</a></td>
        <td>
            <form action="{{ route('mypage.blog.delete',$blog) }}">
                @csrf
                @method('delete')
                <input type="submit" value="削除">
            </form>
        </td>
    @endforeach
</table>

@endsection