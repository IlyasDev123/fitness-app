<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Insight;
use App\Models\Package;
use App\Models\Workout;
use App\Models\UserWorkout;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserSubscriptionHistory;

class DashboardController extends Controller
{
    public function statistics()
    {
        $data['users'] = $this->countState(User::class);
        $data['users']['premium'] = User::where('is_premium', true)->count();
        $data['users']['free'] = User::where('is_premium', false)->count();
        $data['workouts'] = $this->countState(Workout::class);
        $data['insights'] = $this->countState(Insight::class);
        $data['userWorkouts'] = $this->noOfUsersDoingWorkouts();
        $data['subscription'] = $this->subscriptionCount();

        return sendSuccess($data, "success");
    }

    public function countState($model)
    {
        $response = $model::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->cursor();

        $active = $response->where('status', Constants::STATUS['active'])->first();
        $inactive = $response->where('status', Constants::STATUS['inactive'])->first();
        $totalCount = $response->sum('count');
        $state = [
            'active' => $active ? $active->count : 0,
            'inactive' => $inactive ? $inactive->count : 0,
            'total' => $totalCount,

        ];

        return $state;
    }

    public function noOfUsersDoingWorkouts()
    {
        return UserWorkout::selectRaw('workout_id, COUNT(*) as total')->with('workout:id,title')->groupBy('workout_id')->get();
    }

    public function subscriptionCount()
    {
        $response = UserSubscription::selectRaw('is_active, COUNT(*) as count')
            ->groupBy('is_active')
            ->cursor();
        $active = $response->where('is_active', true)->first();
        $inactive = $response->where('is_active', false)->first();
        $totalCount = $response->sum('count');

        $state = [
            'active' => $active ? $active->count : 0,
            'inactive' => $inactive ? $inactive->count : 0,
            'total' => $totalCount,
        ];

        return $state;
    }

    // public function getSubscriptionData(Request $request)
    // {
    //     $data = UserSubscription::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
    //         ->groupBy('month')
    //         ->get();

    //     return response()->json($data);
    // }

    public function getSubscriptionData(Request $request)
    {
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $startDate = $request->start_date;
        $monthlySubscription = UserSubscription::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as count')
        )
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereYear('created_at', $startDate);
            })
            ->groupBy('month')
            ->get();

        $sortedSubscription = collect($months)->map(function ($month) use ($monthlySubscription) {
            $subscription = $monthlySubscription->firstWhere('month', $month);
            return $subscription ?: ['month' => $month, 'count' => 0];
        });

        return sendSuccess($sortedSubscription, "success");
    }

    public function getMostPopularWorkouts()
    {
        $userWorkouts = UserWorkout::selectRaw('workout_id, COUNT(*) as count')
            ->withWhereHas('workout', function ($query) {
                $query->select('id', 'title')->whereNull('deleted_at');
            })
            ->groupBy('workout_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $serlizeData = collect($userWorkouts)->map(function ($userWorkout) {
            return [
                "count" => $userWorkout->count,
                "workout_id" => $userWorkout->workout_id,
                "title" => $userWorkout?->workout?->title,
            ];
        });

        return sendSuccess($serlizeData, "success");
    }

    public function yearlySubscriptionRevenue()
    {
        $userSubscriptionSummary = UserSubscriptionHistory::select('package_id')->withSum('package', 'price')
            ->groupBy('package_id')
            ->get();

        return $userSubscriptionSummary;
    }
}
