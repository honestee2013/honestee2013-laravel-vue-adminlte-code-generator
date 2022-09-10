<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('subject_id')->unsigned();

            $table->enum('name', ['CA','Test','Exams']);
            $table->enum('year', ['2022','2023','2024','2025','2026','2027','2028','2029','2030']);
            $table->enum('term', ['First','Second','Third']);
            $table->enum('sequence', ['First','Second','Third']);
            $table->float("score");

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
        Schema::dropIfExists('assessments');
    }
}
