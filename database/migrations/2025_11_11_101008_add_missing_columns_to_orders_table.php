<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add currency column
            if (!Schema::hasColumn('orders', 'currency')) {
                $table->string('currency')->default('INR')->after('amount');
            }
            
            // Add cart_items column (JSON to store cart data)
            if (!Schema::hasColumn('orders', 'cart_items')) {
                $table->json('cart_items')->nullable()->after('currency');
            }
            
            // Add payment_method column
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->default('razorpay')->after('payment_status');
            }
            
            // Also make sure razorpay_signature is there (if missing)
            if (!Schema::hasColumn('orders', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['currency', 'cart_items', 'payment_method']);
        });
    }
}