<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('tree_count');
            $table->timestamps();
        });

        DB::table('packages')->insert([
            ['name' => 'extrasmall', 'price' => '8000', 'tree_count'=>'1'],
            ['name' => 'small', 'price' => '24000', 'tree_count'=>'3'],
            ['name' => 'medium', 'price' => '40000', 'tree_count'=>'5'],
            ['name' => 'large', 'price' => '80000', 'tree_count'=>'10']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
