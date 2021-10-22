<?php declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     */
    public function testUserリレーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(User::class, $blog->user);
    }

    /**
     *
     */
    public function testCommentsレリーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(Collection::class, $blog->comments);
    }
}
