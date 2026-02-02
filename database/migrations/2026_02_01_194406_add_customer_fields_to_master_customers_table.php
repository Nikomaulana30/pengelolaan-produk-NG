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
        Schema::table('master_customers', function (Blueprint $table) {
            if (!Schema::hasColumn('master_customers', 'kode_customer')) {
                $table->string('kode_customer')->unique()->after('id');
            }
            if (!Schema::hasColumn('master_customers', 'nama_customer')) {
                $table->string('nama_customer')->after('kode_customer');
            }
            if (!Schema::hasColumn('master_customers', 'email_customer')) {
                $table->string('email_customer')->after('nama_customer');
            }
            if (!Schema::hasColumn('master_customers', 'telepon_customer')) {
                $table->string('telepon_customer')->after('email_customer');
            }
            if (!Schema::hasColumn('master_customers', 'alamat_customer')) {
                $table->text('alamat_customer')->after('telepon_customer');
            }
            if (!Schema::hasColumn('master_customers', 'kategori_customer')) {
                $table->enum('kategori_customer', ['vip', 'regular', 'new'])->after('alamat_customer');
            }
            if (!Schema::hasColumn('master_customers', 'payment_terms')) {
                $table->enum('payment_terms', ['cod', '30_days', '45_days', '60_days'])->after('kategori_customer');
            }
            if (!Schema::hasColumn('master_customers', 'credit_limit')) {
                $table->decimal('credit_limit', 15, 2)->default(0)->after('payment_terms');
            }
            if (!Schema::hasColumn('master_customers', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('credit_limit');
            }
            if (!Schema::hasColumn('master_customers', 'phone_contact_person')) {
                $table->string('phone_contact_person')->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('master_customers', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('phone_contact_person');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_customers', function (Blueprint $table) {
            $table->dropColumn([
                'kode_customer',
                'nama_customer',
                'email_customer',
                'telepon_customer',
                'alamat_customer',
                'kategori_customer',
                'payment_terms',
                'credit_limit',
                'contact_person',
                'phone_contact_person',
                'is_active'
            ]);
        });
    }
};
