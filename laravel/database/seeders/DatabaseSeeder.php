<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Blog::factory(15)->create();
        User::factory(15)->create()->each(function ($user) {
            Blog::factory(random_int(2, 5))->seeding()->create(['user_id' => $user->id])->each(function ($blog) {
                Comment::factory(random_int(1, 3))->create(['blog_id' => $blog->id]);
            });
        });

        User::first()->update([
            'name' => '自分',
            'email' => 'aaa@bbb.ccc',
            'password' => bcrypt('hogehoge'),
        ]);
    }
}
