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

            $table->enum('type', ['Pin Code','Token Code']);
            $table->enum('use', ['User Registration', 'Student Result Check']);
            $table->enum('expire', ['After One Week', 'After One Month', 'After Three Months']);
            $table->enum('number_of_use', ['Once', 'Twice', 'Trice', 'Four Times', 'Five Times']);

            $table->bigInteger('used_by')->unsigned()->nullable()->unique(); // User id
            $table->bigInteger('used_times')->unsigned()->nullable();

            $table->string('value')->unique();

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
