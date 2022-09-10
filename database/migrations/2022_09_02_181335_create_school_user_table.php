<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('school_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
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
        Schema::dropIfExists('school_user');
    }
}
