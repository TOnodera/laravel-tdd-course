<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginControllerTest extends TestCase
{
    /**
     * @see App\Http\Controllers\MyPage\UserLoginController
     */
    public function testログイン画面を開ける()
    {
        $this->get('mypage/login')->assertOk();
    }
}
