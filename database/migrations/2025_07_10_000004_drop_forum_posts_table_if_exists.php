<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::dropIfExists('forum_posts');
    }

    public function down(): void
    {
        // No need to recreate here, handled by create migration
    }
}; 