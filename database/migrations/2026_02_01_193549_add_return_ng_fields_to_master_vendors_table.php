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
        Schema::table('master_vendors', function (Blueprint $table) {
            // Add Return NG workflow specific fields
            $table->string('email_vendor')->nullable()->after('email');
            $table->string('telepon_vendor')->nullable()->after('telepon');
            $table->string('contact_person')->nullable()->after('person_in_charge');
            $table->enum('kategori_vendor', ['material', 'component', 'service', 'logistic'])->default('material')->after('contact_person');
            $table->decimal('quality_rating', 3, 2)->default(0)->after('kategori_vendor');
            $table->decimal('delivery_rating', 3, 2)->default(0)->after('quality_rating');
            $table->decimal('service_rating', 3, 2)->default(0)->after('delivery_rating');
            $table->enum('status_vendor', ['active', 'hold', 'blacklist'])->default('active')->after('service_rating');
            $table->date('contract_start')->nullable()->after('status_vendor');
            $table->date('contract_end')->nullable()->after('contract_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_vendors', function (Blueprint $table) {
            $table->dropColumn([
                'email_vendor',
                'telepon_vendor', 
                'contact_person',
                'kategori_vendor',
                'quality_rating',
                'delivery_rating',
                'service_rating',
                'status_vendor',
                'contract_start',
                'contract_end'
            ]);
        });
    }
};
