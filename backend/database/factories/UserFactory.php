<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('en_US');
        return [
            'username' => $faker->unique()->randomElement(['alex', 'jamie', 'taylor', 'morgan', 'casey', 'riley', 'jordan', 'quinn', 'skyler', 'drew', 'avery', 'charlie', 'pat', 'frances', 'jules', 'dana', 'rey', 'robin', 'emery', 'lane']),
            'email' => $faker->unique()->safeEmail(),
            'bio' => $faker->optional()->paragraph(),
            'image' => $faker->optional()->imageUrl(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => $createdAt = $faker->dateTimeThisDecade(),
            'updated_at' => $faker->optional(50, $createdAt)
                ->dateTimeBetween($createdAt),
        ];
    }
}
