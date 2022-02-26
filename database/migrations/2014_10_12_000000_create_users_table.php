<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('dob');
            $table->enum('role', ['SUPER_ADMIN', 'ADMIN', 'USER'])->default('USER');
            $table->enum('status', ['active', 'pending', 'suspend', 'inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
