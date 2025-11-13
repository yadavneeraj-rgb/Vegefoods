<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingDetailsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('payment_method');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('state_city')->nullable()->after('last_name');
            $table->text('street_address')->nullable()->after('state_city');
            $table->string('apartment_suite')->nullable()->after('street_address');
            $table->string('town_city')->nullable()->after('apartment_suite');
            $table->string('postcode')->nullable()->after('town_city');
            $table->string('phone')->nullable()->after('postcode');
            $table->string('email')->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'state_city',
                'street_address',
                'apartment_suite',
                'town_city',
                'postcode',
                'phone',
                'email'
            ]);
        });
    }
}