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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship (bisa untuk berbagai model)
            $table->string('approvable_type')->comment('Model yang perlu approval: PenyimpananNg, DisposisiAssignment, dll');
            $table->unsignedBigInteger('approvable_id')->comment('ID dari model tersebut');
            
            // Approval details
            $table->foreignId('approval_authority_id')->nullable()
                  ->constrained('master_approval_authorities')
                  ->onDelete('set null')
                  ->comment('Authority rule yang digunakan');
            
            $table->foreignId('approver_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User yang akan/sudah approve');
            
            $table->integer('level')->default(1)
                  ->comment('Approval level (1, 2, 3 untuk multi-stage)');
            
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                  ->default('pending');
            
            // Timestamps for approval actions
            $table->timestamp('submitted_at')->nullable()
                  ->comment('Kapan diajukan untuk approval');
            $table->timestamp('approved_at')->nullable()
                  ->comment('Kapan di-approve');
            $table->timestamp('rejected_at')->nullable()
                  ->comment('Kapan di-reject');
            
            // Notes & Reason
            $table->text('notes')->nullable()
                  ->comment('Catatan dari submitter');
            $table->text('approval_notes')->nullable()
                  ->comment('Catatan dari approver');
            $table->text('rejection_reason')->nullable()
                  ->comment('Alasan rejection');
            
            // Audit trail
            $table->foreignId('submitted_by')->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['approvable_type', 'approvable_id']);
            $table->index('approver_id');
            $table->index('status');
            $table->index('level');
            $table->index(['status', 'approver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
