<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InappGoogleController;
use App\Http\Controllers\Apis\v1\AuthController;
use App\Http\Controllers\Apis\v1\UserController;
use App\Http\Controllers\Apis\v1\InsightController;
use App\Http\Controllers\Apis\v1\WorkoutController;
use App\Http\Controllers\InappPurchaseAppleController;
use App\Http\Controllers\Apis\v1\NotificationController;
use App\Http\Controllers\Apis\v1\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('send-otp', 'sendOTP');
        Route::post('verify-otp', 'verifyOTP');
        Route::post('social-login', 'socialLogin');
        Route::post('reset-password', 'resetPassword');
        Route::post('verify-email', 'verifyEmail');
    });

Route::middleware('auth:api')->group(function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('delete', [AuthController::class, 'deleteUser']);

    Route::prefix('users')->controller(UserController::class)
        ->group(function () {
            Route::get('profile', 'getProfile');
            Route::post('update-profile', 'updateProfile');
            Route::post('change-password', 'changePassword');
            Route::post('upload-avatar', 'updateAvatar');
        });

    Route::prefix('workouts')->controller(WorkoutController::class)
        ->group(function () {
            Route::post('all', 'getAllWorkouts');
            Route::get('feature', 'getFeaturedWorkout');
            Route::get('category-base', 'getCategoriesWithWorkouts');
            Route::post('by-category', 'getWorkoutsByCategory');
            Route::post('random-by-category', 'getRandomWorkoutsByCategory');
            Route::post('detail', 'getWorkoutDetail');
            Route::post('schedule', 'setWorkoutSchedule');
            Route::post('favourite', 'favouritWorkout');
            Route::get('favourite-list', 'getFavouritedWorkout');
            Route::post('get-by-date', 'getWorkoutsByDate');
            Route::post('upload-image', 'updloadWorkoutImages');
            Route::post('date-list', 'getScheduleDateByMonth');
            Route::post("start", "startWorkout");
            Route::get('all-favourite-list', 'getAllFavouritedWorkouts');
            Route::post('delete-image', 'deleteWorkoutImage');
        });

    Route::prefix('packages')->controller(SubscriptionController::class)->group(function () {
        Route::get('all', 'getActivePackages');
        Route::get('/detail/{id}', 'getPackageById');
        Route::post('store', 'addPackage');
        Route::post('purchase', 'subscribe');
        Route::get('active-list', 'getActiveSubscription');
    });

    Route::prefix('insights')->controller(InsightController::class)->group(function () {
        Route::get('limited-list', 'getLimitedInsights');
        Route::get('all', 'allInsights');
        Route::get('detail/{id}', 'getInsightDetail');
        Route::post('like', 'likeInsight');
        Route::post('favourit', 'favouritInsight');
        Route::get('favourit-list', 'getFavouritedInsights');
        Route::get('all-favourit-list', 'allFavouritedInsights');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('all', 'getNotifications');
        Route::get('mark-as-read/{id}', 'markAsRead');
        Route::get('count', 'notificationCount');
    });
});

Route::prefix('user/apple')->controller(InappPurchaseAppleController::class)->group(function () {
    Route::post('subscribe', 'inAppSubscribe');
});

Route::prefix('user/google')->controller(InappGoogleController::class)->group(function () {
    Route::post('subscribe', 'inAppSubscribe');
});

Route::prefix('pages')->controller(\App\Http\Controllers\CustomPageController::class)->group(function () {
    Route::get('term-and-condition', 'termAndCondition');
    Route::get('privacy-policy', 'privacyPolicy');
});
