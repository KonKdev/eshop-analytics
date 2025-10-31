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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('store_id');   // ποιο store ανήκει
        $table->bigInteger('order_id');           // WooCommerce ID
        $table->string('status')->nullable();
        $table->decimal('total', 10, 2)->nullable();
        $table->string('currency', 10)->nullable();
        $table->string('customer_name')->nullable();
        $table->timestamp('order_date')->nullable();
        $table->timestamps();

        $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
