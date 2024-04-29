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
        Schema::create('zooms', function (Blueprint $table) {
            $table->bigIncrements('zoom_id');
            $table->dateTime('zoom_date');
            $table->bigInteger('user_id')->unsigned(); 
            $table->string('zoom_url',500);
            $table->string('zoom_password',20);
            $table->string('zoom_meetingid',20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zooms');
    }
};
