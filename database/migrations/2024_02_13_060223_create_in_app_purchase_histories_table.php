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
        Schema::create('in_app_purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->string("transaction_id");
            $table->string("notification_type");
            $table->text("in_app_response");
            $table->string("sub_type")->nullable();
            $table->text('transaction_info')->nullable();
            $table->json('other_info')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_app_purchase_histories');
    }
};
