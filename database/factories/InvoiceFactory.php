<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'invoice_number' => $this->faker->unique()->numerify('INV-#####'),
            'invoice_date' => $this->faker->date(),
            'client_id' => Client::factory(), // Associate with a client
            'grand_total' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
