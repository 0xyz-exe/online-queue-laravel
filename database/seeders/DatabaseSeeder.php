<?php

namespace Database\Seeders;

use App\Models\ServiceLocation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $location = ServiceLocation::firstOrCreate(['slug' => 'central-service-center'], ['name' => 'Central Service Center', 'active' => true]);
        foreach ([['name' => 'General Service', 'prefix' => 'G'], ['name' => 'Payments', 'prefix' => 'P']] as $category) $location->categories()->firstOrCreate(['prefix' => $category['prefix']], $category + ['active' => true]);
        foreach ([['name' => 'Counter 1', 'code' => 'C1'], ['name' => 'Counter 2', 'code' => 'C2']] as $counter) $location->counters()->firstOrCreate(['code' => $counter['code']], $counter + ['active' => true]);
    }
}
