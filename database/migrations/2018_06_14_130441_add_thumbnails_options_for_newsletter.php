<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbnailsOptionsForNewsletter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('middle_photo')->nullable();
            $table->string('right_photo')->nullable();
            $table->string('left_caption')->nullable();
            $table->string('middle_caption')->nullable();
            $table->string('right_caption')->nullable();
            $table->string('left_caption_zh')->nullable();
            $table->string('middle_caption_zh')->nullable();
            $table->string('right_caption_zh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['middle_caption', 'middle_photo', 'right_caption', 'right_photo', 'left_caption', 'left_caption_zh', 'middle_caption_zh', 'right_caption_zh']);
        });
    }
}
