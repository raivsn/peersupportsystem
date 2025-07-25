<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('interval_days')->default(7); // Default to 7 days
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_settings');
    }
}; 