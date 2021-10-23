<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $validData = User::factory()->validData();

        $this->post('signup', $validData)->assertRedirect('mypage/blogs');
        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        //パスワードの検証
        $user = User::firstWhere($validData);
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('abcd1234', $user->password));

        //ログイン確認
        $this->assertAuthenticatedAs($user);
    }

    public function test不正なデータではユーザー登録出来ない()
    {
        $url = 'signup';

        $this->from($url)->post($url, [])
             ->assertRedirect($url);

        $this->post($url, ['name'=>''])->assertSessionHasErrors(['name'=>'名前は必ず指定してください。']);
        $this->post($url, ['name'=>str_repeat('a', 21)])->assertSessionHasErrors(['name' => '名前は、20文字以下で指定してください。']);
        $this->post($url, ['name'=>str_repeat('a', 20)])->assertSessionDoesntHaveErrors('name');

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email'=>'メールアドレスは必ず指定してください。']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertSessionHasErrors(['email'=>'メールアドレスには、有効なメールアドレスを指定してください。']);
        
        User::factory()->create(['email' => 'aa@bb.net']);
        $this->post($url, ['email' => 'aa@bb.net'])->assertSessionHasErrors(['email' => 'メールアドレスの値は既に存在しています。']);

        $this->post($url, ['password'=>''])->assertSessionHasErrors(['password'=>'パスワードは必ず指定してください。']);
        $this->post($url, ['password'=>'abcd123'])->assertSessionHasErrors(['password'=>'パスワードは、8文字以上で指定してください。']);
        $this->post($url, ['password'=>'abcd1234'])->assertSessionDoesntHaveErrors(['password']);
    }
}
