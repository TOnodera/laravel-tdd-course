<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;

class BlogMypageController extends Controller
{
    public function index()
    {
        return view('mypage.blog.index');
    }
}
