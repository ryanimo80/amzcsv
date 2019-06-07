<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Csvdata1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csvdata', function (Blueprint $table) {
            //
            $table->renameColumn('color_name', 'profile_name');
            $table->string('keyword_id');
            $table->integer('design_id');
            $table->integer('design_month');
            $table->string('bulletpoint_1');
            $table->string('bulletpoint_2');
            $table->string('bulletpoint_3');
            $table->string('bulletpoint_4');
            $table->string('bulletpoint_5');
            $table->string('searchterm_1');
            $table->string('searchterm_2');
            $table->string('searchterm_3');
            $table->string('searchterm_4');
            $table->string('searchterm_5');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csvdata', function (Blueprint $table) {
            //
        });
    }
}
