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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'application_submitted', 'application_accepted', 'application_rejected', 'new_job', 'job_updated'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (job_id, application_id, etc.)
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->string('icon')->nullable(); // Icon class for UI
            $table->string('color')->default('primary'); // Notification color theme
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'read']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};