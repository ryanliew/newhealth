<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRmbSupportToPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->float('amount_rmb')->nullable();
            $table->boolean('is_rmb')->default(false);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->float('total_price_rmb')->nullable();
            $table->boolean('is_rmb')->default(false);
        });

        Schema::table('package_purchase', function (Blueprint $table) {
            $table->float('total_price_rmb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount_rmb', 'is_rmb']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['total_price_rmb', 'is_rmb']);
        });

        Schema::table('package_purchase', function (Blueprint $table) {
            $table->dropColumn(['total_price_rmb']);
        });
    }
}
