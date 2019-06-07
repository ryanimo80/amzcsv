<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csvdata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('item_name');
            $table->string('item_sku');
            $table->string('brand_name');
            $table->string('color_name');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csvdata');
    }
}
