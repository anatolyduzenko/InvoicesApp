<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'description' => fake()->text(25),
            'unit' => fake()->randomElement(['hour', 'one-time']),
            'price' => fake()->randomNumber(3),
            'qty' => fake()->randomNumber(2),
            'amount' => fake()->randomNumber(3),
        ];
    }
}
