<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_signs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedInteger('item_id');
            $table->string('item_name');
            $table->string('game_type')->nullable();
            $table->tinyInteger('is_official')->nullable();//正式選手?
            $table->tinyInteger('group_num')->nullable();//組別
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('action_id');
            $table->string('student_year');//年級
            $table->string('student_class');//班級
            $table->tinyInteger('num')->nullable();//座號
            $table->string('sex');
            $table->string('achievement')->nullable();
            $table->unsignedInteger('ranking')->nullable();
            $table->unsignedInteger('order')->nullable();
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
        Schema::dropIfExists('student_signs');
    }
}
