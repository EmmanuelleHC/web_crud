<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the database specified in the .env file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbName = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        config(['database.connections.mysql.database' => null]);

        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`;");
            $this->info("Database '$dbName' created successfully.");
        } catch (\Exception $e) {
            $this->error("Error creating database: " . $e->getMessage());
        }

        config(['database.connections.mysql.database' => $dbName]);
    }
}
