<?php

namespace Database\Factories;

use App\Models\{
    Expense, User
};

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maxDescriptionCharacters = config('values.maximum_description_characters');

        return [
            'description' => fake()->text($maxDescriptionCharacters),
            'date'        => fake()->date,
            'user_id'     => User::factory()->create()->getAttribute('id'),
            'value'       => fake()->randomFloat(2, 1.00, 1000.00)
        ];
    }
}


