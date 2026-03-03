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
        Schema::create('jobs', function (Blueprint $table) {
            $table->comment('The Laravel job table');
            $table->id()->comment('The unique ID for the job');
            $table->string('queue')->index()->comment('The queue for the job');
            $table->longText('payload')->comment('The serialized JSON of the job\'s class, properties, and metadata');
            $table->unsignedTinyInteger('attempts')->comment('The number of times the job has been attempted');
            $table->unsignedInteger('reserved_at')->nullable()->comment('The UNIX timestamp for when a worker started processing the job');
            $table->unsignedInteger('available_at')->comment('The UNIX timestamp for when the job is available for processing, for delayed dispatching');
            $table->unsignedInteger('created_at')->comment('The UNIX timestamp for when the job was initially created or dispatched');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->comment('Laravel table for storing grouped queue jobs');
            $table->string('id')->primary()->comment('A UUID for the batch');
            $table->string('name')->comment('A name for the batch');
            $table->integer('total_jobs')->comment('The total number of jobs assigned to the batch');
            $table->integer('pending_jobs')->comment('The number of jobs which have not finished yet within the batch');
            $table->integer('failed_jobs')->comment('The number of jobs which failed within the batch');
            $table->longText('failed_job_ids')->comment('A list of the specific job IDs which failed');
            $table->mediumText('options')->nullable()->comment('Serialized data such as callbacks to be executed when the batch completes or fails');
            $table->integer('cancelled_at')->nullable()->comment('A UNIX timestamp of if/when the batch was cancelled');
            $table->integer('created_at')->comment('A UNIX timestamp of when the batch was created');
            $table->integer('finished_at')->nullable()->comment('A UNIX timestamp of when all the jobs of the batch have completed or failed');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->comment('Laravel table for storing information about failed jobs which exhausted all their attempts');
            $table->id()->comment('A unique ID for the failed job entry');
            $table->string('uuid')->unique()->comment('A UUID for the job, to support batching and queue:retry');
            $table->text('connection')->comment('The name of the queue which the job was using when it failed');
            $table->text('queue')->comment('The specific queue the job was dispatched to');
            $table->longText('payload')->comment('The serialized JSON of the job\'s class, properties and metadata');
            $table->longText('exception')->comment('The full stack trace and message of the exception which caused the failure');
            $table->timestamp('failed_at')->useCurrent()->comment('The timestamp of when the job failure occurred');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
