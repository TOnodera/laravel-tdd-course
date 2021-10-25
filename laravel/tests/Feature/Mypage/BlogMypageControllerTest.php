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
        $this->post('mypage/blogs/create', [])->assertRedirect($url);
        $this->get('mypage/blogs/edit/1')->assertRedirect($url);
        $this->post('mypage/blogs/edit/1')->assertRedirect($url);
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

    public function testマイページ、ブログを新規登録できる、公開の場合()
    {
        $this->login();
        $validData = Blog::factory()->validData();
        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1')
        ;

        $this->assertDatabaseHas('blogs', $validData);
    }

    public function testマイページ、ブログを新規登録できる、非公開の場合()
    {
        $this->login();
        $validData = Blog::factory()->validData();
        unset($validData['status']);
        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1')
        ;

        $validData['status'] = 0;
        $this->assertDatabaseHas('blogs', $validData);
    }

    public function testマイページ、ブログの登録時入力チェック()
    {
        $url = 'mypage/blogs/create';
        $this->login();
        //$this->from($url)
        //    ->post($url, [])
        //    ->assertRedirect($url);
        app()->setlocale('testing');
        $this->post($url, ['title' => ''])->assertSessionHasErrors(['title' => 'required']);
        $this->post($url, ['title' => str_repeat('a', 256)])->assertSessionHasErrors(['title' => 'max']);
        $this->post($url, ['title' => str_repeat('a', 255)])->assertSessionDoesntHaveErrors(['title' => 'max']);

        $this->post($url, ['body' => ''])->assertSessionHasErrors(['body' => 'required']);
    }

    public function test他人様のブログの編集画面は開けない()
    {
        $blog = Blog::factory()->create();
        $this->login();
        $this->get('mypage/blogs/edit/'.$blog->id)
            ->assertForbidden()
        ;
    }

    public function test他人様のブログを削除できない()
    {
        static::markTestIncomplete('まだ');
    }

    public function test自分のブログの編集画面は開ける()
    {
        $blog = Blog::factory()->create();
        $this->login($blog->user);
        $this->get('mypage/blogs/edit/'.$blog->id)
            ->assertOk()
        ;
    }

    public function test自分のブログは更新できる()
    {
        $validData = [
            'title' => '新タイトル',
            'body' => '新本文',
            'status' => '1',
        ];

        $this->withoutExceptionHandling();
        $blog = Blog::factory()->create();
        $this->login($blog->user);
        $this->post('mypage/blogs/edit/'.$blog->id, $validData)
            ->assertRedirect('mypage/blogs/edit/'.$blog->id)
        ;

        $this->get('mypage/blogs/edit/'.$blog->id)
            ->assertSee('ブログを更新しました。')
        ;

        $this->assertDatabaseHas('blogs', $validData);
        static::assertCount(1, Blog::all());

        $blog->refresh();
        static::assertSame('新タイトル', $blog->title);
        static::assertSame('新本文', $blog->body);
    }

    public function test他人様のブログは更新できない()
    {
        $validData = [
            'title' => '新タイトル',
            'body' => '新本文',
            'status' => '1',
        ];

        //$this->withoutExceptionHandling();
        $blog = Blog::factory()->create();
        $this->login();

        $this->get('mypage/blogs/edit/'.$blog->id, $validData)
            ->assertForbidden()
        ;

        // $this->assertDatabaseMissing('blogs', $validData);

        static::assertCount(1, Blog::all());
        static::assertSame($blog->toArray(), Blog::first()->toArray());
    }
}
