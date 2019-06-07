<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Keywords3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keywords', function (Blueprint $table) {
            //
            $table->text('bulletpoint_1')->change();
            $table->text('bulletpoint_2')->change();
            $table->text('bulletpoint_3')->change();
            $table->text('bulletpoint_4')->change();
            $table->text('bulletpoint_5')->change();
            $table->text('searchterm_1')->change();
            $table->text('searchterm_2')->change();
            $table->text('searchterm_3')->change();
            $table->text('searchterm_4')->change();
            $table->text('searchterm_5')->change();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keywords', function (Blueprint $table) {
            //
        });
    }
}
