<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('question_id');
            $table->bigInteger('lesson_id')->unsigned();
            $table->bigInteger('level_id')->unsigned(); 
            $table->bigInteger('axis_id')->unsigned(); 
            $table->string('question_name',600);
            $table->string('question_type',100);
            $table->bigInteger('question_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
