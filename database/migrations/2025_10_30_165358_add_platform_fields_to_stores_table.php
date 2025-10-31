<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'platform')) {
                $table->string('platform')->default('woocommerce');
            }
            if (!Schema::hasColumn('stores', 'access_token')) {
                $table->string('access_token')->nullable();
            }
            if (!Schema::hasColumn('stores', 'magento_token')) {
                $table->string('magento_token')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'platform')) {
                $table->dropColumn('platform');
            }
            if (Schema::hasColumn('stores', 'access_token')) {
                $table->dropColumn('access_token');
            }
            if (Schema::hasColumn('stores', 'magento_token')) {
                $table->dropColumn('magento_token');
            }
        });
    }
};
