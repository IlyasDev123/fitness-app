<?php

namespace Database\Seeders;

use App\Models\Insight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InsightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $insight =  Insight::create([
                'title' => 'Workout 1',
                'slug' => 'workout-1',
                'description' => 'Workout 1 description',
                'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
                'category_id' => 1,
                'is_featured' => 1,
                "duration" => "00:12:00",
                'status' => 1,
            ]);

            $insight->image()->create([
                'file' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
                'status' => 1,
            ]);

            $insight =  Insight::create([
                'title' => 'Workout 3',
                'slug' => 'workout-2',
                'description' => 'Workout 1 description',
                'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
                'category_id' => 1,
                'is_featured' => 0,
                'status' => 1,
                "duration" => "00:12:00",
            ]);

            $insight->image()->create([
                'file' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
                'status' => 1,
            ]);
        });
    }
}
