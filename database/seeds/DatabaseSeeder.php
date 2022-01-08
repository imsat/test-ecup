<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::truncate();
        Category::truncate();
        Post::truncate();

        factory(User::class, 50)->create();
        $categories = factory(Category::class, 20)->create();

        $categories->each(function ($category) {
            factory(Post::class, 5)->create(['category_id' => $category->_id]);
        });
    }
}
