<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workout_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained('workouts')->cascadeOnDelete();
            $table->string('url');
            $table->string('thumbnail');
            $table->time('duration');
            $table->boolean('is_premium')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();

            $table->index(['url', 'workout_id', 'is_premium', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_videos');
    }
};
