<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = FakerFactory::create('en_US');

        // Create 20 users with English data
        $usernames = ['alex', 'jamie', 'taylor', 'morgan', 'casey', 'riley', 'jordan', 'quinn', 'skyler', 'drew', 'avery', 'charlie', 'pat', 'frances', 'jules', 'dana', 'rey', 'robin', 'emery', 'lane'];

        for ($i = 0; $i < 20; $i++) {
            User::create([
                'username' => $usernames[$i] ?? $faker->userName(),
                'email' => $faker->unique()->safeEmail(),
                'bio' => $faker->optional()->paragraph(),
                'image' => $faker->optional()->imageUrl(),
                'password' => bcrypt('password'),
                'created_at' => $faker->dateTimeThisDecade(),
                'updated_at' => $faker->dateTimeThisDecade(),
            ]);
        }

        $users = User::all();

        // Create user followers
        foreach ($users as $user) {
            $user->followers()->attach($users->random(rand(0, 5))->pluck('id'));
        }

        // Create tags with readable English names
        $tagNames = ['technology', 'science', 'design', 'health', 'culture', 'sports', 'food', 'travel', 'business', 'music', 'art', 'education', 'programming', 'lifestyle', 'finance', 'entertainment', 'fashion', 'nature', 'photography', 'personal'];

        foreach ($tagNames as $name) {
            Tag::create(['name' => $name, 'created_at' => $faker->dateTimeThisDecade(), 'updated_at' => $faker->dateTimeThisDecade()]);
        }

        $tags = Tag::all();

        // Create articles
        for ($i = 0; $i < 30; $i++) {
            $article = Article::create([
                'author_id' => $users->random()->id,
                'slug' => Str::slug($faker->unique()->sentence(4)),
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph(),
                'body' => $faker->paragraphs(3, true),
                'created_at' => $faker->dateTimeThisDecade(),
                'updated_at' => $faker->dateTimeThisDecade(),
            ]);

            $article->tags()->attach($tags->random(rand(1, 6))->pluck('id'));
            $article->favoredUsers()->attach($users->random(rand(0, 8))->pluck('id'));
        }

        $articles = Article::all();

        // Create comments
        for ($i = 0; $i < 60; $i++) {
            Comment::create([
                'article_id' => $articles->random()->id,
                'author_id' => $users->random()->id,
                'body' => $faker->sentence(),
                'created_at' => $faker->dateTimeThisDecade(),
                'updated_at' => $faker->dateTimeThisDecade(),
            ]);
        }
    }
}