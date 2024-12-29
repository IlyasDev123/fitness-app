<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutResource;
use App\Contracts\WorkoutServiceInterface;
use App\Http\Resources\WorkoutBaseCategory;
use App\Http\Requests\Workout\SchedulRequest;
use App\Http\Requests\Workout\WorkoutIdRequest;
use App\Http\Requests\Workout\DeleteImageRequest;
use App\Http\Requests\Workout\ImageUploadRequest;
use App\Http\Resources\FavouritedWorkoutResource;
use App\Http\Requests\Workout\CategoryByIdRequest;
use App\Http\Requests\Workout\WorkoutStartRequest;

use function PHPUnit\Framework\isEmpty;

class WorkoutController extends Controller
{
    public function __construct(protected WorkoutServiceInterface $workoutService)
    {
    }

    public function getFeaturedWorkout()
    {
        $featureWorkout = $this->workoutService->getFeaturedWorkout();
        $data = WorkoutResource::make($featureWorkout);
        $data = $featureWorkout ? $data : null;

        return sendSuccess($data, 'Success');
    }

    public function getWorkoutsByCategory(CategoryByIdRequest $request)
    {
        $response = $this->workoutService->getWorkoutsByCategory($request);
        $response = WorkoutResource::collection($response);

        return sendSuccess($response, 'Success', paginate($response));
    }

    public function getRandomWorkoutsByCategory(CategoryByIdRequest $request)
    {
        $response = $this->workoutService->getRandomWorkoutsByCategory($request);
        $response = WorkoutResource::collection($response);

        return sendSuccess($response, 'Success', paginate($response));
    }

    public function getUserWorkouts(Request $request)
    {
        $response = $this->workoutService->getUserWorkouts($request->user());
        $response =  WorkoutResource::collection($response);

        return sendSuccess($response, 'Success', paginate($response));
    }

    public function getWorkoutDetail(WorkoutIdRequest $request)
    {
        $data = $this->workoutService->getWorkoutDetail($request->workout_id);
        $data = WorkoutResource::make($data);
        return sendSuccess($data, 'Success');
    }

    public function getCategoriesWithWorkouts()
    {
        $data = $this->workoutService->getCategoriesWithWorkouts();
        $data = WorkoutBaseCategory::collection($data);

        return sendSuccess($data, 'Success');
    }

    public function setWorkoutSchedule(SchedulRequest $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->only('user_id', 'workout_id', json_decode('date'));
            $data = $this->workoutService->setWorkoutSchedule($request);
            DB::commit();
            return sendSuccess($data, 'The workout has been scheduled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return sendError($e->getMessage(), $e->getCode());
        }
    }

    public function favouritWorkout(WorkoutIdRequest $request)
    {
        $data = $this->workoutService->favouritWorkout($request->only('workout_id'));
        if (isset($data['attached']) && $data['attached']) {
            $data['is_favorite'] = true;
        } else {
            $data['is_favorite'] = false;
        }

        return sendSuccess($data, 'Success');
    }

    public function getFavouritedWorkout()
    {
        $latestWorkout = $this->workoutService->getLatestFavouritedWorkout();
        $data = $this->workoutService->getFavouritedWorkouts($latestWorkout?->id);
        $favouritedWorkout = WorkoutResource::collection($data);
        $latestWorkoutResource =  WorkoutResource::make($latestWorkout);

        return sendSuccess(
            [
                'latest_workout' => $latestWorkout ? $latestWorkoutResource : null,
                'favourited_workout' => $favouritedWorkout,
            ],
            'Success',
        );
    }

    public function getAllFavouritedWorkouts()
    {
        $data = $this->workoutService->getAllFavouritedWorkouts();
        $favouritedWorkout = WorkoutResource::collection($data);

        return sendSuccess($favouritedWorkout, 'Success', paginate($favouritedWorkout));
    }

    public function getWorkoutsByDate(Request $request)
    {
        $data = $this->workoutService->getWorkoutsByDate($request->date ?? now());
        $response = WorkoutResource::collection($data);

        return sendSuccess($response, 'Success', paginate($response));
    }

    public function updloadWorkoutImages(ImageUploadRequest $request)
    {
        try {
            $data = $this->workoutService->updloadWorkoutImages($request->only('workout_id', 'images'));
            return sendSuccess($data, 'Success');
        } catch (\Throwable $th) {
            return sendError($th->getMessage(), 400);
        }
    }

    public function getAllWorkouts(Request $request)
    {
        $data = $this->workoutService->getAllWorkouts($request);
        $data = WorkoutResource::collection($data);

        return sendSuccess($data, 'Success', paginate($data));
    }

    public function getScheduleDateByMonth(Request $request)
    {
        $data = $this->workoutService->getScheduleDate($request->all());
        return sendSuccess($data, "Success");
    }

    public function startWorkout(WorkoutStartRequest $request)
    {
        try {
            $data = $this->workoutService->startWorkout($request->all());
            return sendSuccess($data, "success");
        } catch (\Throwable $th) {
            return sendError("Something went wrong.Please try again later." . $th->getMessage(), 400);
        }
    }

    public function deleteWorkoutImage(DeleteImageRequest $request)
    {
        try {
            $data = $this->workoutService->deleteWorkoutImage($request->all());
            return sendSuccess($data, "success");
        } catch (\Throwable $th) {
            return sendError("Something went wrong.Please try again later.");
        }
    }
}
