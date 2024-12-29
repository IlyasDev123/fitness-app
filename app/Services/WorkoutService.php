<?php

namespace App\Services;

use App\Services\FileHubService;
use Illuminate\Support\Facades\DB;
use App\Repositories\WorkoutRepository;
use App\Contracts\WorkoutServiceInterface;
use App\Models\Workout;

class WorkoutService implements WorkoutServiceInterface
{

    public function __construct(protected WorkoutRepository $workoutRepository, protected FileHubService $fileService)
    {
    }

    public function create(array $data)
    {
        $workout = null;
        if (isset($data['thumbnail']) && $data['thumbnail']) {
            $thumbnail =  storeImagesOnSThree('workouts/thumbnails/', $data['thumbnail']);
        } else {
            $thumbnail = $this->fileService->genrateThumbnail($data['url'], "workouts/thumbnails/", "s3");
        }
        DB::transaction(function () use ($data, $thumbnail, &$workout) {
            $data['thumbnail'] = $thumbnail;
            $data['is_featured'] = $data['is_featured'] === "true";
            $data['status'] = $data['status'] === "true" ? 1 : 0;
            $data['is_premium'] = true;
            $workout = $this->workoutRepository->createWorkout($data);
            $workout->video()->create([
                'url' => $data['url'],
                'duration' => $data['duration'],
                'thumbnail' => $thumbnail,
            ]);
        });

        return $workout;
    }

    public function update(array $data)
    {

        $workout = $this->workoutRepository->findBy(['id', $data['workout_id']]);
        $video = $workout->video;
        $thumbnail = null;
        if (isset($data['thumbnail']) && $data['thumbnail']) {
            $thumbnail =  storeImagesOnSThree('workouts/thumbnails/', $data['thumbnail']);
        }
        DB::transaction(function () use ($data, $thumbnail, $workout, $video) {
            $data['thumbnail'] = $thumbnail ?? $video->getRawOriginal('thumbnail');
            $data['is_featured'] = $data['is_featured'] === "true";
            $data['status'] = $data['status'] === "true" ? 1 : 0;
            $workout->update($data);
            $workout->videos()->update([
                'url' => $data['url'] == $video->url ? $video->getRawOriginal('url') : $data['url'],
                'duration' => $data['duration'],
                'thumbnail' => $data['thumbnail'],
            ]);
        });

        return $workout;
    }

    public function getAllWorkouts($request)
    {
        return $this->workoutRepository->getAllWorkouts($request);
    }

    public function getFeaturedWorkout()
    {
        return $this->workoutRepository->getFeaturedWorkout();
    }

    public function getWorkoutsByCategory($request)
    {
        return $this->workoutRepository->getWorkoutsByCategory($request);
    }

    public function getRandomWorkoutsByCategory($request)
    {
        return $this->workoutRepository->getRandomWorkoutsByCategory($request);
    }

    public function getUserWorkouts(array $userData)
    {
        return \App\Models\UserWorkout::where('user_id', $userData['user_id'])->get();
    }

    public function getWorkoutDetail(int $id)
    {
        return $this->workoutRepository->getWorkoutDetail($id, ['video', 'category:id,name', 'isFavourited']);
    }

    public function getCategoriesWithWorkouts()
    {
        return $this->workoutRepository->getCategoriesWithWorkouts();
    }

    public function setWorkoutSchedule(array $data)
    {
        $workouts = $this->workoutRepository->findBy(['id', $data['workout_id']]);
        $data = collect($data['date'])->map(function ($item) use ($data) {
            return [
                'user_id' => auth()->id(),
                'date' => $item,
            ];
        });
        $workouts->scheduleWorkouts()->createMany($data);

        return $workouts->load('video', 'category:id,name');
    }

    public function favouritWorkout(array $data)
    {
        $workouts = $this->workoutRepository->findBy(['id', $data['workout_id']]);
        return $workouts->favourits()->toggle(auth()->id());
    }

    public function getFavouritedWorkouts($id = null)
    {
        return $this->workoutRepository->getFavouritedWorkouts($id);
    }

    public function getLatestFavouritedWorkout()
    {
        return $this->workoutRepository->getLatestFavouritedWorkout();
    }

    public function getWorkoutsByDate($date)
    {
        return $this->workoutRepository->getWorkoutsByDate($date);
    }

    public function updloadWorkoutImages($request)
    {
        $workout = $this->workoutRepository->findBy(['id', $request['workout_id']]);
        $images = [];
        foreach ($request['images'] as $image) {
            $images[] = [
                'image' => storeFiles('workouts', $image),
                'user_id' => auth()->id(),
            ];
        }
        $workout->images()->createMany($images);

        return $workout->load('images');
    }

    public function uploadVideo($data)
    {
        return $this->fileService->uploadVideo($data['video'], 'workouts/videos');
    }

    public function getScheduleDate($data)
    {
        return $this->workoutRepository->getScheduleDate($data);
    }

    public function startWorkout(array $data)
    {
        $data['watched_time'] = isset($data['watched_time']) ? $data['watched_time'] : "00:00:00";
        $data['is_played'] = true;

        return $this->workoutRepository->startWorkout($data);
    }

    public function getAllFavouritedWorkouts()
    {
        return $this->workoutRepository->getAllFavouritedWorkouts();
    }

    public function deleteWorkoutImage($data)
    {
        return $this->workoutRepository->deleteImage($data['id']);
    }
}
