<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('forum_posts', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('category_id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->string('title');
        //     $table->text('content');
        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('forum_posts');
    }
}; 