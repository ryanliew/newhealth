<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKycFieldsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_identity')->nullable();
            $table->string('kyc_residence_proof')->nullable();
            $table->string('kyc_nominee_identity')->nullable();
            $table->string('kyc_bank_statement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kyc_identity', 'kyc_bank_statement', 'kyc_nominee_identity', 'kyc_residence_proof']);
        });
    }
}
