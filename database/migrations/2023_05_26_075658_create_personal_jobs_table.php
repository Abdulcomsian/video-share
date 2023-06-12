<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); 
            $table->unsignedBigInteger('folder_id');
            $table->string('title');
            $table->longText('description');
            $table->double('budget', 8, 2);
            $table->dateTime("deadline");
            $table->string('status');
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
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
        Schema::dropIfExists('personal_jobs');
    }
}
