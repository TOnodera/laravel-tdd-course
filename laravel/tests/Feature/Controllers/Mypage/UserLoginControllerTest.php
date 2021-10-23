<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Mypage;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 *
 * @see App\Http\Controllers\MyPage\UserLoginController
 */
final class UserLoginControllerTest extends TestCase
{
    public function testログイン画面を開ける()
    {
        $this->get('mypage/login')->assertOk();
    }

    public function testログイン時の入力チェック()
    {
        $url = 'mypage/login';
        // $this->from($url)->post($url, [])->assertRedirect($url);

        app()->setLocale('testing'); //　テスト用ロケール

        $this->post($url, ['email'=>''])->assertSessionHasErrors(['email'=>'required']);
        $this->post($url, ['email'=>'aa@bb@cc'])->assertSessionHasErrors(['email'=>'email']);
        $this->post($url, ['email'=>'aa@ああ.こむ'])->assertSessionHasErrors(['email'=>'email']);

        $this->post($url, ['password'=>''])->assertSessionHasErrors(['password'=>'required']);
    }
}
