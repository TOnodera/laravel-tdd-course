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
        $blog1 = Blog::factory()->create(['status' => Blog::CLOSED, 'title' => 'ブログA']);
        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();
        static::assertFalse($blogs->contains($blog1));
        static::assertTrue($blogs->contains($blog2));
        static::assertTrue($blogs->contains($blog3));
    }
}
