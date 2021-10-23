<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login(User $user = null)
    {
        // 認証済みの場合
        $user = $user ?? User::factory()->create();
        $this->actingAs($user);

        return $user;
    }
}
