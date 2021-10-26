<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 *
 * @see App\Http\Controllers\MyPage\UserLoginController
 */
final class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testログイン画面を開ける()
    {
        $this->get('mypage/login')->assertOk();
    }

    public function testログイン時の入力チェック()
    {
        $url = 'mypage/login';
        // $this->from($url)->post($url, [])->assertRedirect($url);

        app()->setLocale('testing'); //　テスト用ロケール

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'aa@ああ.こむ'])->assertSessionHasErrors(['email' => 'email']);

        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
    }

    public function testログインできる()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('mypage/login', $postData)
            ->assertRedirect('mypage/blogs')
        ;

        $this->assertAuthenticatedAs($user);
    }

    public function IDを間違えているのでログイン出来ない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);
        $url = 'mypage/login';
        $this->from($url)->followingRedirects()->post($url, $postData)
            ->assertSee('メールアドレスかパスワードが間違っています。')
        ;
    }

    public function パスワードを間違えているのでログイン出来ない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd567'),
        ];

        $user = User::factory()->create($dbData);
        $url = 'mypage/login';
        $this->from($url)->followingRedirects()->post($url, $postData)
            ->assertSee('メールアドレスかパスワードが間違っています。')
        ;
    }

    public function test認証エラーなのでValidationExceptionの例外が発生する()
    {
        $this->withoutExceptionHandling(); // 例外発生のテストでは必ず必要。ないとハンドリングされてしまう。

        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        // $this->expectException(ValidationException::class);

        try {
            $this->post('mypage/login', $postData);
            static::fail('ValidationExceptionの例外が発生しませんでした。');
        } catch (ValidationException $e) {
            static::assertEquals('メールアドレスかパスワードが間違っています。', $e->errors()['email'][0]);
        }
    }

    public function test認証OKなのでvalidationExceptionの例外がでない()
    {
        $this->withoutExceptionHandling(); // 例外発生のテストでは必ず必要。ないとハンドリングされてしまう。
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        User::factory()->create($dbData);

        try {
            $this->post('mypage/login', $postData);
            static::assertTrue(true);
        } catch (ValidationException $e) {
            static::fail('ValidationExceptionの例外が発生しました。');
        }
    }

    public function testログアウトできる()
    {
        $this->login();
        $this->post('mypage/logout')
            ->assertRedirect('mypage/login')
        ;

        $this->get('mypage/login')->assertSee('ログアウトしました。');
        $this->assertGuest();
    }
}
