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
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('answer_id');
            $table->bigInteger('question_id')->unsigned();
            $table->string('question_value', 100)->nullable();
            $table->boolean('question_isTrue')->default(false)->nullable();
            $table->string('question_right', 100)->nullable();
            $table->string('question_left', 100)->nullable(); 
            $table->integer('question_count')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
};
