<?php

use Illuminate\Database\Migrations\Migration;

class AddAwdColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            'listings',
            function ($table) {
                $table->integer('awd');
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
        Schema::table(
            'listings',
            function ($table) {
                $table->dropColumn('awd');
            }
        );

	}

}