<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEntry>
 */
class CalendarEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $datePublished = fake()->unique()->dateTimeThisDecade();
        return [
            'date_published' => $datePublished,
            'title' => fake()->words(3, true),
            'content' => fake()->paragraphs(4, true),
            'slug' => $datePublished->format('d-m-Y'),
            'highlighted' => false
        ];
    }
}
