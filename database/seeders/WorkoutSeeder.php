<?php

namespace Database\Seeders;

use App\Models\Workout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workout =  Workout::create([
            'title' => 'Workout 1',
            'description' => 'Workout 1 description',
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            'category_id' => 1,
            'is_featured' => 1,
            'is_premium' => 0,
            'status' => 1,
        ]);

        $workout->videos()->create([
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            'url' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
            "duration" => "00:12:00",
            'status' => 1,
        ]);

        $workout =  Workout::create([
            'title' => 'Workout 2',
            'description' => 'Workout 2 description',
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            'category_id' => 1,
            'is_featured' => 0,
            'is_premium' => 1,
            'status' => 1,
        ]);

        $workout->videos()->create([
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            "duration" => "00:12:00",
            'url' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
            'status' => 1,
        ]);

        $workout =  Workout::create([
            'title' => 'Workout 3',
            'description' => 'Workout 1 description',
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            'category_id' => 2,
            'is_featured' => 0,
            'is_premium' => 1,
            'status' => 1,
        ]);

        $workout->videos()->create([
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            "duration" => "00:12:00",
            'url' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
            'status' => 1,
        ]);

        $workout =  Workout::create([
            'title' => 'Workout 4',
            'description' => 'Workout 4 description',
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            'category_id' => 3,
            'is_featured' => 0,
            'is_premium' => 1,
            'status' => 1,
        ]);

        $workout->videos()->create([
            'thumbnail' => 'https://i.ytimg.com/vi/1zPYW6I5ZzE/maxresdefault.jpg',
            "duration" => "00:12:00",
            'url' => 'https://d1qp2v0u65kwmf.cloudfront.net/workouts/lessons/videos/OtP3IfZbjB2GcR3UcHJ64qnTy29CAHaH2bkbNUww.mp4',
            'status' => 1,
        ]);
    }
}
