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
        Schema::create('user_image_posts', function (Blueprint $table) {
            $table->id();
            $table->string('video_image');
            $table->string('status')->default('pending');
            $table->string('message')->default('No');
            $table->timestamps();
            $table->integer('video_id');
            $table->string('user_name');
            $table->boolean('post')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->date('date')->default(date("Y-m-d"));
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_image_posts');
    }
};
