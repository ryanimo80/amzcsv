<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Keywords1 extends Migration
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
            $table->renameColumn('searchtearm_1','searchterm_1');
            $table->renameColumn('searchtearm_2','searchterm_2');
            $table->renameColumn('searchtearm_3','searchterm_3');
            $table->renameColumn('searchtearm_4','searchterm_4');
            $table->renameColumn('searchtearm_5','searchterm_5');
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
