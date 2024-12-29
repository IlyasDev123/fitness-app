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
        Schema::create('insight_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insight_id')->constrained('insights')->onDelete('cascade');
            $table->string('file');
            $table->tinyInteger('type')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['insight_id', 'type', 'status', 'file']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insight_files');
    }
};
