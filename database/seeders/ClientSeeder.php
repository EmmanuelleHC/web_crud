<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::insert([
            [
                'client_number' => 'C001',
                'client_name' => 'John Doe',
                'client_address' => '123 Main St, Springfield, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_number' => 'C002',
                'client_name' => 'Jane Smith',
                'client_address' => '456 Elm St, Metropolis, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_number' => 'C003',
                'client_name' => 'Robert Johnson',
                'client_address' => '789 Oak St, Gotham, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
