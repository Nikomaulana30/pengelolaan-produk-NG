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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship
            $table->string('traceable_type'); // Model class name
            $table->unsignedBigInteger('traceable_id'); // Model ID
            
            // Action type
            $table->string('action'); // created, status_changed, approved, rejected, etc
            
            // User who performed action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Details
            $table->string('description'); // Human readable description
            $table->longText('old_value')->nullable(); // Previous value
            $table->longText('new_value')->nullable(); // New value
            $table->json('metadata')->nullable(); // Additional data
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['traceable_type', 'traceable_id']);
            $table->index('action');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
