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
        Schema::table('customers', function (Blueprint $table) {
            $table->text('address')->nullable()->after('phone');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('shop_phone');
            $table->string('shop_email')->nullable()->after('shop_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'shop_email']);
        });
    }
};
