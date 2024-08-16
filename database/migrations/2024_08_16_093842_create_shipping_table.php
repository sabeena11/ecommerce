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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->unsignedBigInteger('order_id');
            $table->string('shipping_address', 255);
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('Nepal');
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->string('shipping_method', 100);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};
