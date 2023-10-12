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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->bigIncrements('userAnswer_id');
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('lesson_id')->unsigned();
            $table->bigInteger('level_id')->unsigned(); 
            $table->bigInteger('axis_id')->unsigned(); 
            $table->string('userAnswer_question',600);
            $table->string('userAnswer_answer',600)->nullable();
            $table->boolean('userAnswer_isTrue')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_answers');
    }
};
