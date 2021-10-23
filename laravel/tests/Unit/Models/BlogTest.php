<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testUserリレーションを返す()
    {
        $blog = Blog::factory()->create();
        static::assertInstanceOf(User::class, $blog->user);
    }

    public function testCommentsレリーションを返す()
    {
        $blog = Blog::factory()->create();
        static::assertInstanceOf(Collection::class, $blog->comments);
    }

    public function testブログの公開非公開のスコープ()
    {
        $blog1 = Blog::factory()->closed()->create(['title' => 'ブログA']);
        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();
        static::assertFalse($blogs->contains($blog1));
        static::assertTrue($blogs->contains($blog2));
        static::assertTrue($blogs->contains($blog3));
    }

    public function ブログで非公開時はtrue、公開時はfalseを返す()
    {
        $blog = Blog::factory()->make();
        static::assertFalse($blog->isClosed());

        $blog = Blog::factory()->closed()->make();
        static::assertTrue($blog->isClosed());
    }
}
