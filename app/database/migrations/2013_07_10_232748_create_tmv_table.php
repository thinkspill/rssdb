<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTmvTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tmv',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('year');
                $table->string('make');
                $table->string('model');
                $table->integer('tmv');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tmv');
    }

}
