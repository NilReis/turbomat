<?php

namespace Database\Seeders;

use App\Models\Chapa;
use Illuminate\Database\Seeder;

class ChapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chapa::factory()
            ->count(5)
            ->create();
    }
}
