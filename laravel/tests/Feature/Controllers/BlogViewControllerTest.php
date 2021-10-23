<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function testブログの詳細画面を表示出来る()
    {
        $blog = Blog::factory()->create();

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name);
    }

    public function testブログで非公開のものは、詳細画面は表示できない()
    {
        $blog = Blog::factory()->closed()->create();
        $this->get('blogs/'.$blog->id)
            ->assertForbidden();
    }

    public function testクリスマスの日は、メリークリスマスと表示される()
    {
        $blog = Blog::factory()->create();

        Carbon::setTestNow('2021-12-24');
        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス');

        Carbon::setTestNow('2021-12-25');
        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス');
    }
}
