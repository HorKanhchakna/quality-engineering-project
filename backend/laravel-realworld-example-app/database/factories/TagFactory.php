<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $locale = 'en_US';

    public function definition()
    {
        $createdAt = $this->faker->dateTimeThisDecade();

        return [
            'name' => $this->faker->unique()->randomElement(['technology', 'science', 'design', 'health', 'culture', 'sports', 'food', 'travel', 'business', 'music', 'art', 'education', 'programming', 'lifestyle', 'finance', 'entertainment', 'fashion', 'nature', 'photography', 'personal']),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}