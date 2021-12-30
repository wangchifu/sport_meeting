<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedInteger('action_id');
            $table->unsignedInteger('order')->nullable();//排序
            $table->string('game_type')->nullable();//個人賽或團體賽
            $table->tinyInteger('official')->nullable();//正式選手數
            $table->tinyInteger('reserve')->nullable();//預備選手數
            $table->string('name');//
            $table->tinyInteger('group');//組別 1男子組 2女子組 3男子組+女子組
            $table->tinyInteger('type');//類別 1徑賽  2田賽  3其他
            $table->string('years');//哪些年級報名
            $table->tinyInteger('limit')->nullable();//1限制選乎參賽項目
            $table->tinyInteger('people');//此項目每班可報名幾名
            $table->tinyInteger('reward');//錄取名次
            $table->tinyInteger('disable')->nullable();//
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
