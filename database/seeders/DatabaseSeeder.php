<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CatalogSeeder::class,
        ]);

        $this->call(UserSeeder::class);

        $this->call(EventSeeder::class);
    }
}