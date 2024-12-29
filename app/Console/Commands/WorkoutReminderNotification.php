<?php

namespace App\Console\Commands;

use App\Models\User;
use Mockery\Matcher\Not;
use App\Models\Notification;
use App\Models\WorkoutSchedule;
use Illuminate\Console\Command;
use App\Http\Traits\FirebaseNotificationTrait;

class WorkoutReminderNotification extends Command
{
    use FirebaseNotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:workout-reminder-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send workout reminder notification to users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workouts = WorkoutSchedule::whereDate('date', now()->format('Y-m-d'))->cursor();
        $workouts->each(function ($item) {
            $user = User::with('device')->find($item->user_id);
            if (!$user) {
                return;
            }
            Notification::create([
                'content' => 'You have a workout scheduled for today.',
                'user_id' => $user->id,
                'type' => 1,
                'notifiable_type' => 'App\Models\Workout',
                'notifiable_id' => $item->workout_id,
                'is_read' => false,
            ]);

            $data = [
                'data' => [
                    'type' => 'workout',
                    'workout_id' => $item->workout_id,
                ],
            ];

            $this->sendFirebaseNotification($user->device->fcm_token, 'Workout Reminder', 'You have a workout scheduled for today.', $data['data']);
        });

        $this->info('Workout reminder notification sent successfully.');
    }
}
