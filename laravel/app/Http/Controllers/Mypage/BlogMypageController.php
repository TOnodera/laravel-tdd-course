<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogMypageController extends Controller
{
    public function index()
    {
        // $blogs = Blog::where('user_id', auth()->user()->id)->get();
        $blogs = auth()->user()->blogs;

        return view('mypage.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('mypage.blog.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateInput();
        $data['status'] = $request->boolean('status');
        $blog = auth()->user()->blogs()->create($data);

        return redirect('mypage/blogs/edit/'.$blog->id);
    }

    public function edit(Request $request, Blog $blog)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }
        $data = old() ?: $blog;

        return view('mypage.blog.edit', compact('blog', 'data'));
    }

    public function update(Request $request, Blog $blog)
    {
        // 所有者チェック
        $data = $this->validateInput();
        $data['status'] = $request->boolean('status');

        $blog->update($data);

        return redirect(route('mypage.blog.edit', $blog))
            ->with('status', 'ブログを更新しました。')
        ;
    }

    private function validateInput()
    {
        return request()->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
    }
}
