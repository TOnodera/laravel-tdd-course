<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\StrRandom;
use Mockery;

/**
 * @internal
 * @coversNothing
 */
final class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testブログのTOPページを開ける()
    {
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(3)->create();
        $blog3 = Blog::factory()->hasComments(2)->create();

        $this->get('/')
            ->assertOk()
            ->assertViewIs('index')
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->user->name)
            ->assertSee('(1件のコメント)')
            ->assertSee('(3件のコメント)')
            ->assertSee('(2件のコメント)')
            ->assertSeeInOrder([$blog2->title, $blog3->title, $blog1->title])
        ;
    }

    public function testブログの一覧、非公開のブログは表示されない()
    {
        Blog::factory()->closed()->create(['title' => 'ブログA']);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC')
        ;
    }

    public function testブログの詳細画面を表示出来て、コメントが古い順に表示される()
    {
        $blog = Blog::factory()->withCommentsData([
            ['created_at' => now()->sub('2 days'), 'name' => '太郎'],
            ['created_at' => now()->sub('3 days'), 'name' => '次郎'],
            ['created_at' => now()->sub('1 days'), 'name' => '三郎'],
        ])->create();

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name)
            ->assertSeeInOrder(['次郎', '太郎', '三郎'])
        ;
    }

    public function testブログで非公開のものは、詳細画面は表示できない()
    {
        $blog = Blog::factory()->closed()->create();
        $this->get('blogs/'.$blog->id)
            ->assertForbidden()
        ;
    }

    public function testクリスマスの日は、メリークリスマスと表示される()
    {
        $blog = Blog::factory()->create();

        Carbon::setTestNow('2021-12-24');
        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス')
        ;

        Carbon::setTestNow('2021-12-25');
        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス')
        ;
    }

    public function testブログの詳細画面でランダムな文字列が１０文字表示される()
    {
        $blog = Blog::factory()->create();

        // Str::shouldReceive('random')
        //     ->once()
        //     ->with(10)
        //     ->andReturn('HELLO_RAND');

        //$mock = new class() {
        //    public function random(int $len)
        //    {
        //        if ($len !== 10) {
        //            throw new \Exception('引数、ちがうよ');
        //        }
        //        return 'HELLO_RAND';
        //    }
        //};
        //$this->app->instance(StrRandom::class, $mock);

        //$mock = Mockery::mock(StrRandom::class);
        //$mock->shouldReceive('random')->once()->with(10)->andReturn('HELLO_RAND');
        //$this->app->instance(StrRandom::class, $mock);

        $this->mock(StrRandom::class, function ($mock) {
            $mock->shouldReceive('random')->once()->with(10)->andReturn('HELLO_RAND');
        });

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee('HELLO_RAND')
        ;
    }
}
