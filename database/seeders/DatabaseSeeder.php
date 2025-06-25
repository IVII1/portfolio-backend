<?php

namespace Database\Seeders;

use App\Models\CalendarEntry;
use App\Models\Category;
use App\Models\Reference;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        CalendarEntry::factory(50)->create();
        Category::factory(20)->create();
        Reference::factory(30)->create();
    }
}
