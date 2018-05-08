<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            $table->string('avatar_path')->nullable();
            $table->string('social_service')->nullable();
            $table->string('social_id')->nullable();
            $table->string('referral_code')->nullable();
            $table->unsignedInteger('country_id')->nullable();

            $table->string('identification')->nullable();
            $table->string('nationality')->nullable();
            $table->string('gender')->nullable();
            $table->unsignedInteger('address_id')->nullable();

            $table->string('company_name')->nullable();
            $table->string('company_business_registration')->nullable();
            $table->string('company_incorporation_place')->nullable();
            $table->date('company_incorporation_date')->nullable();
            $table->string('company_type')->nullable();
            $table->unsignedInteger('company_address_id')->nullable();
            $table->string('company_email')->nullable();

            $table->string('bank_name')->nullable();
            $table->string('bank_swift')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_no')->nullable();
            $table->string('beneficiary_name')->nullable();
            
            $table->nestedSet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::dropIfExists('users');
    }
}
