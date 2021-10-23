<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Mypage;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class UserLoginControllerTest extends TestCase
{
    /**
     * @see App\Http\Controllers\MyPage\UserLoginController
     */
    public function testログイン画面を開ける()
    {
        $this->get('mypage/login')->assertOk();
    }
}
