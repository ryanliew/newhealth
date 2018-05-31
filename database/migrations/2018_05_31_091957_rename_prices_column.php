<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePricesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('total_price_rmb', 'total_price_std');
            //$table->renameColumn('is_rmb', 'is_std');
        });

        Schema::table('purchases', function (Blueprint $table) {
            //$table->renameColumn('total_price_rmb', 'total_price_std');
            $table->renameColumn('is_rmb', 'is_std');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('amount_rmb', 'amount_std');
            // $table->renameColumn('is_rmb', 'is_std');
        });

        Schema::table('payments', function (Blueprint $table) {
            // $table->renameColumn('amount_rmb', 'amount_std');
            $table->renameColumn('is_rmb', 'is_std');
        });

        Schema::table('package_purchase', function (Blueprint $table) {
            $table->renameColumn('total_price_rmb', 'total_price_std');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->renameColumn('price_rmb', 'price_std');
            //$table->renameColumn('price_rmb_promotion', 'price_std_promotion');
        });

         Schema::table('packages', function (Blueprint $table) {
            // $table->renameColumn('price_rmb', 'price_std');
            $table->renameColumn('price_rmb_promotion', 'price_std_promotion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('total_price_std', 'total_price_rmb');
            //$table->renameColumn('is_std', 'is_rmb');
        });

        Schema::table('purchases', function (Blueprint $table) {
            // $table->renameColumn('total_price_std', 'total_price_rmb');
            $table->renameColumn('is_std', 'is_rmb');
        });


        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('amount_std', 'amount_rmb');
            //$table->renameColumn('is_std', 'is_rmb');
        });

        Schema::table('payments', function (Blueprint $table) {
            // $table->renameColumn('amount_std', 'amount_rmb');
            $table->renameColumn('is_std', 'is_rmb');
        });

        Schema::table('package_purchase', function (Blueprint $table) {
            $table->renameColumn('total_price_std', 'total_price_rmb');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->renameColumn('price_std', 'price_rmb');
            //$table->renameColumn('price_std_promotion', 'price_rmb_promotion');
        });

        Schema::table('packages', function (Blueprint $table) {
            // $table->renameColumn('price_std', 'price_rmb');
            $table->renameColumn('price_std_promotion', 'price_rmb_promotion');
        });
    }
}
