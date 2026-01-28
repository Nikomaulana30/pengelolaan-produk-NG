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
        Schema::table('penerimaan_barangs', function (Blueprint $table) {
            // Master Lokasi Gudang FK - lokasi penyimpanan barang
            $table->unsignedBigInteger('master_lokasi_gudang_id')->nullable()->after('keterangan');
            
            // Detail lokasi (auto-filled from master)
            $table->string('zone')->nullable()->after('master_lokasi_gudang_id');
            $table->string('rack')->nullable()->after('zone');
            $table->string('bin')->nullable()->after('rack');
            $table->string('lokasi_lengkap')->nullable()->after('bin');
            
            // Status penerimaan
            $table->enum('status_penerimaan', ['diterima', 'sedang_inspeksi', 'selesai_inspeksi', 'disimpan', 'ditolak'])
                ->default('diterima')
                ->after('lokasi_lengkap');
            
            // Catatan QC inspection
            $table->text('hasil_inspeksi')->nullable()->after('status_penerimaan');
            $table->boolean('ada_defect')->default(false)->after('hasil_inspeksi');
            
            // Foreign key constraint
            $table->foreign('master_lokasi_gudang_id')
                ->references('id')
                ->on('master_lokasi_gudangs')
                ->onDelete('set null');
            
            $table->index('master_lokasi_gudang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan_barangs', function (Blueprint $table) {
            $table->dropForeign(['master_lokasi_gudang_id']);
            $table->dropIndex(['master_lokasi_gudang_id']);
            $table->dropColumn([
                'master_lokasi_gudang_id',
                'zone',
                'rack',
                'bin',
                'lokasi_lengkap',
                'status_penerimaan',
                'hasil_inspeksi',
                'ada_defect'
            ]);
        });
    }
};
