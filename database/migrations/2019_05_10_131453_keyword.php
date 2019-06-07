<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Keyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('keyword_group_name');
            $table->mediumText('bulletpoint_1');
            $table->mediumText('bulletpoint_2');
            $table->mediumText('bulletpoint_3');
            $table->mediumText('bulletpoint_4');
            $table->mediumText('bulletpoint_5');
            $table->mediumText('searchtearm_1');
            $table->mediumText('searchtearm_2');
            $table->mediumText('searchtearm_3');
            $table->mediumText('searchtearm_4');
            $table->mediumText('searchtearm_5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
