<?php

// app/Contracts/AuthServiceInterface.php
namespace App\Contracts;

interface WorkoutServiceInterface
{
    public function create(array $data);
    public function update(array $data);
    public function getAllworkouts($request);
    public function getFeaturedWorkout();
    public function getUserWorkouts(array $userData);
    public function getWorkoutDetail(int $id);
    public function getCategoriesWithWorkouts();
    public function getWorkoutsByCategory($request);
    public function getRandomWorkoutsByCategory($request);
    public function setWorkoutSchedule(array $data);
    public function favouritWorkout(array $data);
    public function getFavouritedWorkouts($id = null);
    public function getLatestFavouritedWorkout();
    public function getWorkoutsByDate($date);
    public function updloadWorkoutImages($request);
    public function uploadVideo($request);
    public function getScheduleDate(array $data);
    public function startWorkout(array $data);
    public function getAllFavouritedWorkouts();
    public function deleteWorkoutImage(array $data);
}
