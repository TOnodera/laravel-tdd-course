<?php declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogViewControllerTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function testブログのTOPページを開ける()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/');
        $response->assertOk();
    }
}
