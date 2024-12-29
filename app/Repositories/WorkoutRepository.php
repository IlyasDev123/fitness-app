<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\Workout;
use App\Models\Category;
use App\Models\UserWorkout;
use App\Models\WorkoutImage;
use App\Models\WorkoutSchedule;

class WorkoutRepository
{

    public function findBY(array $data)
    {
        return Workout::where([$data])->first();
    }

    public function getAllWorkouts($request)
    {
        return Workout::activeWorkout()->with('video', 'watchedVideo', 'category', 'isFavourited')
            ->when($request->search, fn ($q) => $q->search($request->search))->orderByDesc('id')->paginate($request->limit ?? prePageLimit());
    }

    public function getWorkoutsByCategory($request)
    {
        return Workout::with(['video', 'category:id,name', 'watchedVideo', 'isFavourited'])->where(['category_id' => $request->category_id, 'is_premium' => true])->activeWorkout()
            ->when($request->search, fn ($q) => $q->search($request->search))
            ->orderByDesc('id')->paginate($request->limit ?? prePageLimit());
    }

    public function getRandomWorkoutsByCategory($request)
    {
        return Workout::with(['video', 'category:id,name', 'watchedVideo', 'isFavourited'])->where(['category_id' => $request->category_id, 'is_premium' => true])->activeWorkout()
            ->when($request->search, fn ($q) => $q->search($request->search))
            ->inRandomOrder()->paginate($request->limit ?? prePageLimit());
    }

    public function getWorkoutDetail(int $id, $relations)
    {
        return Workout::with($relations)->find($id);
    }

    public function getFeaturedWorkout()
    {
        return Workout::with('video', 'watchedVideo', 'category', 'isFavourited')->activeWorkout()->where('is_featured', true)->first();
    }

    public function getCategoriesWithWorkouts()
    {
        $categories = Category::has('workouts')->with(['workouts' => function ($query) {
            $query->where('is_featured', false)->activeWorkout()->with('video', 'watchedVideo', 'isFavourited');
        }])->where('type', Constants::CATEGORY_TYPE['workout'])->orderBy('sort_order', 'asc')->get();

        return $categories;
    }

    public function setScheduleWorkout($workouts, $data)
    {
        return $workouts->scheduleWorkouts()->saveMany($data);
    }

    public function getWorkoutsByDate($date)
    {
        return Workout::activeWorkout()->with(['video:id,workout_id,duration,thumbnail', 'category:id,name', 'isFavourited', 'watchedVideo', 'images' => fn ($q) => $q->userImage()])->whereHas('scheduleWorkouts', function ($query) use ($date) {
            $query->where('date', $date)->where('user_id', auth()->id());
        })->paginate(prePageLimit());
    }

    public function getFavouritedWorkouts($id = null)
    {
        return Workout::activeWorkout()->with('video', 'watchedVideo', 'category', 'isFavourited')
            ->when($id, fn ($q) => $q->whereNot('id', $id))
            ->whereHas('favourits', fn ($q) => $q->where('user_id', auth()->id()))
            ->orderByOtherTable('favourit_workout', 'workouts.id', 'workout_id')
            ->limit(prePageLimit())->get();
    }

    public function getAllFavouritedWorkouts()
    {
        $searchTerm = request()->query('search');
        return Workout::activeWorkout()->when($searchTerm, fn ($q) => $q->search($searchTerm))->with('video', 'watchedVideo', 'category', 'isFavourited')
            ->whereHas('favourits', fn ($q) => $q->where('user_id', auth()->id()))
            ->orderByOtherTable('favourit_workout', 'workouts.id', 'workout_id')
            ->paginate(prePageLimit());
    }

    public function getLatestFavouritedWorkout()
    {
        return Workout::activeWorkout()->with('video', 'watchedVideo', 'category', 'isFavourited')->whereHas('favourits', fn ($q) => $q->where('user_id', auth()->id()))
            ->orderByOtherTable('favourit_workout', 'workouts.id', 'workout_id')
            ->first();
    }

    public function createWorkout(array $data)
    {
        return Workout::create($data);
    }

    public function getScheduleDate($data)
    {
        return WorkoutSchedule::select('id', 'date')->whereBetween('date', [$data['start_date'], $data['end_date']])->where('user_id', auth()->id())->cursor();
    }

    public function startWorkout($data)
    {
        return UserWorkout::updateOrCreate(
            ['user_id' => auth()->id(), 'workout_id' => $data['workout_id']],
            [
                'watched_time' => $data['watched_time'],
                'is_played' => true,
                'workout_video_id' => $data['workout_video_id']
            ]

        );
    }

    public function deleteImage($id)
    {
        return WorkoutImage::find($id)->delete();
    }
}
