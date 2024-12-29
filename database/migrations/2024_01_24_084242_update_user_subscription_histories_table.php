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
        Schema::table('user_subscription_histories', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscription_histories', function (Blueprint $table) {
            $table->dropForeign('package_id');
            $table->dropColumn(['package_id']);
        });
    }
};