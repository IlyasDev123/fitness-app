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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('inapp_package_id')->nullable()->unique();
            $table->float('price', 8, 2)->default(0);
            $table->jsonb(('description'))->nullable();
            $table->tinyInteger('duration')->default(1)->comment('1 => daily, 2 => weekly, 3 =>monthly, 4 => yearly,  5=> lifetime');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name', 'inapp_package_id', 'price', 'is_active']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
