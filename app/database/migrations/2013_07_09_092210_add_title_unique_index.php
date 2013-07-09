<?php

use Illuminate\Database\Migrations\Migration;

class AddTitleUniqueIndex extends Migration {

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
                $table->unique('title');
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
                $table->dropUnique('title');
            }
        );

	}

}