<?php

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test index()
     */
    function ブログのトップページを開ける(){
        $blog1 = Blog::factory()->create();
        $blog2 = Blog::factory()->create();
        $blog3 = Blog::factory()->create();


        $this
            ->get('/')
            ->assertOk()
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title);
    }
}
