<?php

namespace Database\Seeders;

use App\Models\ChapaItem;
use Illuminate\Database\Seeder;

class ChapaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChapaItem::factory()
            ->count(5)
            ->create();
    }
}
