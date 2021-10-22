<?php

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
     * @test
     */
    public function userリレーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(User::class, $blog->user);
    }

    /**
     * @test
     */
    public function commentsレリーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(Collection::class, $blog->comments);
    }
}
