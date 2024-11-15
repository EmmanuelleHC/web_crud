<?php
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Inspiration is everywhere!');
})->purpose('Display an inspiring quote');

Artisan::command('db:create', function () {
    $dbName = config('database.connections.mysql.database');
    $charset = config('database.connections.mysql.charset', 'utf8mb4');
    $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

    config(['database.connections.mysql.database' => null]);

    try {
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE $collation;");
        $this->info("Database '$dbName' created successfully.");
    } catch (\Exception $e) {
        $this->error("Error creating database: " . $e->getMessage());
    }

    config(['database.connections.mysql.database' => $dbName]);
})->purpose('Create the database specified in the .env file');
