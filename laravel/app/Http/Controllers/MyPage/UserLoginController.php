<?php

declare(strict_types=1);

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage.login');
    }
}
