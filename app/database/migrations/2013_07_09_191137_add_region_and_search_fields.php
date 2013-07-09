<?php

use Illuminate\Database\Migrations\Migration;

class AddRegionAndSearchFields extends Migration
{

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
                $table->string('region');
                $table->string('search');
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
                $table->dropColumn('region');
                $table->dropColumn('search');
            }
        );

    }

}