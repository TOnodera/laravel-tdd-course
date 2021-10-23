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
}
