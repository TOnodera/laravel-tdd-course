<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testユーザー登録画面を開ける()
    {
        $this->get('signup/')->assertOk();
    }

    public function testユーザー登録できる()
    {
        //データの検証
        //DBに保存
        //ログインさせてからマイページにリダイレクト

        $validData  = [
            'name' => '太郎',
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234'
        ];

        $this->post('signup', $validData)->assertOk();
        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        //パスワードの検証
        $user = User::firstWhere($validData);
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('abcd1234', $user->password));
    }
}
