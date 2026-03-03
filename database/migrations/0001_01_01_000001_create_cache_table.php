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
        Schema::create('cache', function (Blueprint $table) {
            $table->comment('Laravel cache table');
            $table->string('key')->primary()->comment('The key of the cached value');
            $table->mediumText('value')->comment('The cached value');
            $table->integer('expiration')->index()->comment('The UNIX timestamp for when the value expires');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->comment('Laravel cache table locks');
            $table->string('key')->primary()->comment('The key of the locked cache entry');
            $table->string('owner')->comment('The owner of the cache lock');
            $table->integer('expiration')->index()->comment('The UNIX timestamp for when the cache lock expires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
