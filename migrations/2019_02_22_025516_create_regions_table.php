<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->unsignedMediumInteger('id')->primary()->comment("编码");
            $table->unsignedMediumInteger('pid')->index()->comment("上级编码");
            $table->char('city_code',4)->index()->comment("区号");
            $table->string("name")->comment("名称");
            $table->string("spell")->comment("拼音等 用空格隔开");
            $table->string("abbr")->comment("首字母缩写");
            $table->tinyInteger("level")->comment("级别");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
