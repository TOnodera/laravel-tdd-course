<?php

declare(strict_types=1);

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email:filter'],
            'password' => ['required']
        ]);
    }
}
