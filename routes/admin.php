<?php

use Illuminate\Support\Facades\Route;

Route::controller(App\Http\Controllers\Admin\AuthController::class)
    ->group(function () {
        Route::post('login', 'login');
        Route::post('profile', 'register');
        Route::post('send-otp', 'sendOTP');
        Route::post('verify-otp', 'verifyOTP');
        Route::post('social-login', 'socialLogin');
        Route::post('reset-password', 'resetPassword');
        Route::post('verify-email', 'verifyEmail');
    });

Route::middleware('admin')->group(function () {

    Route::controller(App\Http\Controllers\Admin\AuthController::class)
        ->group(function () {
            Route::get('logout', 'logout');
        });

    Route::prefix('user')->controller(App\Http\Controllers\Admin\UserController::class)
        ->group(function () {
            Route::get('profile', 'getProfile');
            Route::post('update-profile', 'updateProfile');
            Route::post('change-password', 'passwordReset');
            Route::get('all', 'getUsers');
            Route::post('update-status', 'updateStatus');
            Route::get('show/{id}', 'getUser');
        });

    Route::prefix('workouts')->controller(App\Http\Controllers\Admin\WorkoutController::class)
        ->group(function () {
            Route::get('get', 'getAllWorkouts');
            Route::post('upload-video', 'uploadVideo');
            Route::post('create', 'create');
            Route::post('update', 'update');
            Route::get('show/{id}', 'getWorkout');
            Route::delete('delete/{id}', 'delete');
            Route::post('update-feature-status', 'updateFeatureStatus');
            Route::post('update-premium-status', 'updatePremiumStatus');
            Route::post('update-status', 'updateStatus');
        });

    Route::prefix('categories')->controller(App\Http\Controllers\Admin\CategoryController::class)
        ->group(function () {
            Route::get('all', 'getCategories');
            Route::get('workout', 'getWorkoutCategories');
            Route::get('insight', 'getInsightCategories');
            Route::post('create', 'createCategory');
            Route::put('update/{id}', 'updateCategory');
            Route::delete('delete/{id}', 'deleteCategory');
            Route::post('update-status', 'updateStatus');
            Route::post('create-insight', 'createInsightCategory');
            Route::post('sort', 'sortCategories');
        });

    Route::prefix('insights')->controller(App\Http\Controllers\Admin\InsightController::class)
        ->group(function () {
            Route::get('all', 'index');
            Route::post('store', 'store');
            Route::post('update', 'update');
            Route::get('show/{id}', 'show');
            Route::delete('delete/{id}', 'destroy');
            Route::post('update-status', 'updateStatus');
        });

    Route::prefix('dashboard')->controller(App\Http\Controllers\Admin\DashboardController::class)
        ->group(function () {
            Route::get('statistics', 'statistics');
            Route::get('subscription', 'getSubscriptionData');
            Route::get('top-workout-state', 'getMostPopularWorkouts');
            Route::get('revenue', 'yearlySubscriptionRevenue');
        });

    Route::prefix('packages')->controller(App\Http\Controllers\Admin\PackageController::class)
        ->group(function () {
            Route::post('create', 'createPackage');
            Route::post('update', 'updatePackage');
            Route::get('all', 'getPackages');
            Route::post('update-status', 'updateStatus');
            Route::get('show/{id}', 'getPackage');
            Route::delete('delete/{id}', 'delete');
        });

    Route::prefix('subscriptions')->controller(App\Http\Controllers\Admin\SubscriptionController::class)->group(function () {
        Route::get('all', 'getSubscriptions');
        Route::get('show/{id}', 'getSubscriptionDetail');
    });

    Route::prefix('pages')->controller(App\Http\Controllers\Admin\PageController::class)
        ->group(function () {
            Route::get('all', 'getAll');
            Route::get('show/{id}', 'get');
            Route::post('create', 'create');
            Route::post('update', 'update');
            Route::delete('delete/{id}', 'delete');
        });

    Route::prefix('faqs')->controller(App\Http\Controllers\Admin\FaqController::class)
        ->group(function () {
            Route::get('all', 'getAll');
            Route::get('show/{id}', 'get');
            Route::post('create', 'create');
            Route::post('update', 'update');
            Route::delete('delete/{id}', 'delete');
            Route::post('sort', 'sortFaqs');
        });
});
