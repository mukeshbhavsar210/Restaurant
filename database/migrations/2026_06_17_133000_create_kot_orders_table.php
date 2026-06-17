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
        Schema::create('kot_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name')->nullable();
            $table->string('price');
            $table->string('quantity');            
            $table->string('order_no')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('cgst', 10, 2)->default(0);
            $table->decimal('sgst', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', [
                'draft',
                'running',
                'billed',
                'completed',
                'cancelled'
            ])->default('draft');

            $table->timestamps();

            $table->index('seat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kot_orders');
    }
};
