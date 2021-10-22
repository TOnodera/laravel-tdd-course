<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogViewController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->onlyOpen()->withCount('comments')->orderByDesc('comments_count')->get();
        return view('index', compact('blogs'));
    }
}
