<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use App\StrRandom;

//use Illuminate\Support\Str;

class BlogViewController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->onlyOpen()->withCount('comments')->orderByDesc('comments_count')->get();

        return view('index', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        if ($blog->isClosed()) {
            abort(403);
        }

        // $random = (new StrRandom)->random(10);
        $random = resolve(StrRandom::class)->random(10);

        return view('blog.show', compact('blog', 'random'));
    }
}
