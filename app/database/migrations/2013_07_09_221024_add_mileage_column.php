<?php

use Illuminate\Database\Migrations\Migration;

class AddMileageColumn extends Migration {

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
                $table->integer('mileage');
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
                $table->dropColumn('mileage');
            }
        );

	}

}