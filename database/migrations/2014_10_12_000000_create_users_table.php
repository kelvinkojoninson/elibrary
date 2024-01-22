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
            $table->string('userid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('picture')->nullable();
            $table->enum('gender',['MALE','FEMALE'])->nullable();
            $table->string('country')->nullable();
            $table->date('dob')->nullable();
            $table->string('two_step')->nullable();
            $table->string('deactivation_reason')->nullable();
            $table->string('role_id')->nullable();
            $table->string('password')->nullable();
            $table->string('firebaseKey')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->enum('status', ['ACTIVE','INACTIVE','SUSPENDED'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('modifyuser')->nullable();
            $table->string('createuser')->nullable();
            $table->softDeletes();
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
