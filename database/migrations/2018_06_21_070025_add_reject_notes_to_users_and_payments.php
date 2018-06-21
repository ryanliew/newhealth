<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectNotesToUsersAndPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->text("reject_note")->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text("reject_note")->nullable();
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
            $table->dropColumn(['reject_note']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reject_note']);
        });
    }
}
