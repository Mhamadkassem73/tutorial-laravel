<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\user ;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_name',50);
                $table->string('password',100);
                $table->boolean('user_isActive')->default(true);
                $table->string('user_pushToken',100)->nullable();
                $table->string('user_role',10);
                $table->string('user_email',100)->nullable();
                $table->string('user_phone',100)->nullable();
                $table->string('name',100)->nullable();
                $table->string('user_level',100)->nullable();
    
                
            });
    
            user ::query () -> create ( [
                'user_name' => "admin" ,
                'password' => bcrypt ( "0000" ) ,
                'user_isActive' => true ,
                'user_role' => "admin" ,
            ] );
    
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
};
