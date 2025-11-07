<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('product_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->decimal('mrp_base_price', 10, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);
            $table->timestamps();

            $table->unique('product_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_pricing');
    }
};