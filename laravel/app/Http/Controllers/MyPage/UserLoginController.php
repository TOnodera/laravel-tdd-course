<?php

declare(strict_types=1);

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:filter'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスかパスワードが間違っています。']);
        }

        return redirect('mypage/blogs');
    }
}
