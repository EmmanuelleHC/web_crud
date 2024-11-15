<?php
namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'client_number' => $this->faker->unique()->numerify('CLT-#####'),
            'client_name' => $this->faker->name,
            'client_address' => $this->faker->address,
        ];
    }
}
