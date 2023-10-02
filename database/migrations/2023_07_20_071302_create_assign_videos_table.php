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
        Schema::create('assign_videos', function (Blueprint $table) {
            $table->id();
            $table->string('channle_name');
            $table->string('thumbnail');
            $table->string('video_link');
            $table->text('instructions');
            $table->integer('max_video');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_videos');
    }
};
