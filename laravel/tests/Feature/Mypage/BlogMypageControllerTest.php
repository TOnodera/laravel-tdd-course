<?php

declare(strict_types=1);

namespace Tests\Feature\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 * @see App\Http\Controllers\Mypage\BlogMypageController
 */
final class BlogMypageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test認証している場合に限り、マイページを開ける()
    {
        // $this->withoutExceptionHandling();
        // 認証している場合
        $this->get('mypage/blogs')
            ->assertRedirect('mypage/login');
        
        // 認証済みの場合
        $this->login();
        $this->get('mypage/blogs')->assertOk();
    }
}
