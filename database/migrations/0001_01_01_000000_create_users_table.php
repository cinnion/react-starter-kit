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
            $table->comment('The table of registered users');
            $table->id()->comment('The unique ID for the user');
            $table->string('name')->comment('The user\'s name');
            $table->string('email')->unique()->comment('The user\'s email address they use to log in');
            $table->timestamp('email_verified_at')->nullable()->comment('When the user\'s email address was verified');
            $table->string('password')->comment('The user\'s hashed password');
            $table->rememberToken()->comment('The user\'s token for Remember Me');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->comment('The table of user\'s password reset tokens');
            $table->string('email')->primary()->comment('The user\'s email address');
            $table->string('token')->comment('The user\'s password reset token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->comment('The table of user\'s sessions and session data');
            $table->string('id')->primary()->comment('The unique ID for the user\'s session');
            $table->foreignId('user_id')->nullable()->index()->comment('The user\'s user_id');
            $table->string('ip_address', 45)->nullable()->comment('The user\'s IP address');
            $table->text('user_agent')->nullable()->comment('The user\'s user_agent');
            $table->longText('payload')->comment('The session payload');
            $table->integer('last_activity')->index()->comment('The UNIX timestamp for the user\'s last activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
