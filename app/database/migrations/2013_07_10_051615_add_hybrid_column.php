<?php

use Illuminate\Database\Migrations\Migration;

class AddHybridColumn extends Migration {

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
                $table->integer('hybrid');
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
                $table->dropColumn('hybrid');
            }
        );

	}

}