<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned();
            $table->string('user_type');

            $table->enum('code_type', ['Pin','Token']);
            $table->enum('use_for', ['User registration', 'Student result check']);
            $table->enum('expire_time', ['After one week', 'After one month']);
            $table->enum('maximum_use', [1, 3, 5]);

            $table->bigInteger('used_by')->unsigned()->nullable()->unique(); // User id
            $table->bigInteger('number_of_use')->unsigned()->nullable();

            $table->string('value')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('codes');
    }
}
