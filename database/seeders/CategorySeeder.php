<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "id" => 1,
                "name" => "Essentials For Beginners",
                "status" => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "id" => 2,
                "name" => "Essentials For Intermediate",
                "status" => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "id" => 3,
                "name" => "Essentials For Advance",
                "status" => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($data);
    }
}
