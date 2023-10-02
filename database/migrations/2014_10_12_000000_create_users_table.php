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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('phone');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('joining_date');
            $table->string('trx_id');
            $table->string('fee_image');
            $table->string('refferal');
            $table->string('referance_no');
            $table->string('package');
            $table->boolean('status')->default(0);
            $table->boolean('type')->default(2);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
