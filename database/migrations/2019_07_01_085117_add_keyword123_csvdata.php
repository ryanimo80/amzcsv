<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeyword123Csvdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('csvdata', function (Blueprint $table) {
            $table->string('keyword')->unable();//main keyword
            $table->string('keyword_1')->unable();
            $table->string('keyword_2')->unable();
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
