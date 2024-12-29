<?php

namespace App\Http\Controllers\Admin;

use App\Models\Workout;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Contracts\WorkoutServiceInterface;
use App\Http\Requests\Workout\StoreRequest;
use App\Http\Requests\Workout\FeatureRequest;
use App\Http\Requests\Workout\PremiumRequest;
use App\Http\Requests\Workout\UploadFileRequest;
use App\Http\Requests\Workout\UpdateStatusRequest;

class WorkoutController extends Controller
{
    public function __construct(protected WorkoutServiceInterface $workService)
    {
        $this->middleware('admin');
    }

    public function create(StoreRequest $request)
    {
        try {
            $data = $this->workService->create($request->all());
            return sendSuccess($data, 'Workout created successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function uploadVideo(UploadFileRequest $request)
    {
        try {
            $data = $this->workService->uploadVideo($request);
            return sendSuccess($data, 'Video uploaded successfully.');
        } catch (\Throwable $th) {
            return sendErrorResponse("Something went wrong. Please try again later." . $th->getMessage());
        }
    }

    public function update(StoreRequest $request)
    {
        try {
            $data = $this->workService->update($request->all());
            return sendSuccess($data, 'Workout updated successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }


    public function getAllWorkouts()
    {
        $status = request()->query('status');
        $workouts = Workout::with('video', 'category:id,name')
            ->when(request()->query('search'), fn ($q) => $q->search(request()->query('search')))->orderByDesc('id')
            ->when(request()->query('category_id'), fn ($q) => $q->filterByCategory(request()->query('category_id')))
            ->when($status, fn ($q) => $q->filterByStatus($status))
            ->paginate(prePageLimit());
        return sendSuccess($workouts, 'Success');
    }

    public function getWorkout($id)
    {
        $workout = Workout::with('video', 'category:id,name')->find($id);
        return sendSuccess($workout, 'Success');
    }

    public function delete($id)
    {
        try {
            $workout =  Workout::find($id);
            if ($workout->is_featured == true) {
                throw new \Exception('Featured workout can not be deleted.');
            }
            $workout->delete();
            return sendSuccess(null, 'Workout deleted successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function updateFeatureStatus(FeatureRequest $request)
    {
        try {

            $workout = Workout::find($request->id);
            DB::transaction(function () use ($request, $workout) {
                $workout->is_featured = $request->is_featured;
                $workout->is_premium = !$request->is_featured;
                $workout->save();
                if ($request->is_featured == true) {
                    Workout::where('id', '!=', $request->id)->where('is_featured', $request->is_featured)
                        ->update(['is_featured' => false, 'is_premium' => $request->is_featured]);
                } else {
                    throw new \Exception('Atleast one workout should be featured.');
                }
            });

            return sendSuccess($workout, 'Workout has been marked as a featured workout');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function updatePremiumStatus(PremiumRequest $request)
    {
        try {
            $workout = Workout::find($request->id);
            $workout->is_premium = $request->is_premium;
            $workout->save();

            return sendSuccess($workout, 'Workout has been marked as a premium workout');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            $status = $request->status == true ? 1 : 0;
            $workout = Workout::find($request->id);
            $workout->status = $status;
            $workout->save();

            return sendSuccess($workout, 'Workout status has been updated successfully.');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }
}
