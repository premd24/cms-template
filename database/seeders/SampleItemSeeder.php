<?php

namespace Database\Seeders;

use App\Models\SampleItem;
use Illuminate\Database\Seeder;

class SampleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Starter Item Alpha', 'description' => 'This is a sample item demonstrating text inputs and tables.', 'status' => 'active'],
            ['name' => 'Starter Item Beta', 'description' => 'Another boilerplate sample entry for layout preview.', 'status' => 'active'],
            ['name' => 'Draft Entry Gamma', 'description' => 'A sample item that is marked inactive for testing filters.', 'status' => 'inactive'],
            ['name' => 'Starter Item Delta', 'description' => 'Demonstrates validation and editing logic.', 'status' => 'active'],
            ['name' => 'Starter Item Epsilon', 'description' => 'Shows pagination and search features in the DataTable.', 'status' => 'active'],
        ];

        foreach ($items as $item) {
            SampleItem::create($item);
        }
    }
}
