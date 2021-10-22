<?php declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
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
            ->assertSee("(1件のコメント)")
            ->assertSee("(3件のコメント)")
            ->assertSee("(2件のコメント)")
            ->assertSeeInOrder([$blog2->title,$blog3->title,$blog1->title]);
    }

    /**
     * @test
     */
    public function testブログの一覧、非公開のブログは表示されない()
    {
        Blog::factory()->create(['status'=>Blog::CLOSED,'title'=>'ブログA']);
        Blog::factory()->create(['title'=>'ブログB']);
        Blog::factory()->create(['title'=>'ブログC']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC');
    }
}
