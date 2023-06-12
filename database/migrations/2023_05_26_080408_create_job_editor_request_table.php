<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobEditorRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_editor_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('request_id');
            $table->foreign('editor_id')->references('id')->on('users');
            $table->foreign('job_id')->references('id')->on('personal_jobs');
            $table->foreign('request_id')->references('id')->on('requests');
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
        Schema::dropIfExists('job_editor_request');
    }
}
