<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;

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
        $quantity = $this->faker->numberBetween(1, 10);
        $unitPrice = $this->faker->randomFloat(2, 1, 100);

        return [
            'item_id' => $this->faker->unique()->numberBetween(1, 1000),
            'item_name' => $this->faker->words(3, true), // Generates a name like "Premium Widget Set"
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice, // Calculate total price
            'invoice_id' => Invoice::factory(), // Associate with an Invoice
        ];
    }
}
