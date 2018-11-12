<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionPictureNullabledTreecountToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->string('package_photo_path')->nullable();
            $table->integer('tree_count')->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn("description");
            $table->dropColumn("package_photo_path");
            $table->integer("tree_count")->nullable(false)->change();
        });
    }
}
