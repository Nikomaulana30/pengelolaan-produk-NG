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
        Schema::table('quality_inspections', function (Blueprint $table) {
            // Add approval fields
            $table->string('status_approval')->nullable()->after('approved_by');
            $table->text('catatan_approval')->nullable()->after('status_approval');
            $table->string('nama_approver')->nullable()->after('catatan_approval');
            $table->dateTime('tanggal_approval')->nullable()->after('nama_approver');

            // Add indexes
            $table->index('status_approval');
            $table->index('tanggal_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_inspections', function (Blueprint $table) {
            $table->dropIndex(['status_approval']);
            $table->dropIndex(['tanggal_approval']);
            $table->dropColumn(['status_approval', 'catatan_approval', 'nama_approver', 'tanggal_approval']);
        });
    }
};
