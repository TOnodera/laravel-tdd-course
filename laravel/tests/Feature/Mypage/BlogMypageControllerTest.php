<?php

declare(strict_types=1);

namespace Tests\Feature\Mypage;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 *
 * @see App\Http\Controllers\Mypage\BlogMypageController
 */
final class BlogMypageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testゲストはブログを管理できない()
    {
        $url = 'mypage/login';
        $this->get('mypage/blogs')
            ->assertRedirect('mypage/login')
        ;
        $this->get('mypage/blogs/create')->assertRedirect($url);
    }

    public function testマイページブログ一覧で自分のデータのみ表示される()
    {
        // $this->withoutExceptionHandling();
        // 認証済みの場合
        $user = $this->login();
        $myblog = Blog::factory()->create(['user_id' => $user->id]);
        $other = Blog::factory()->create();

        $this
            ->get('mypage/blogs')
            ->assertOk()
            ->assertDontSee($other->title)
            ->assertSee($myblog->title)
        ;
    }

    public function testマイページ、ブログの新規登録画面を開ける()
    {
        $this->login();
        $this->get('mypage/blogs/create')
            ->assertOk()
        ;
    }
}
