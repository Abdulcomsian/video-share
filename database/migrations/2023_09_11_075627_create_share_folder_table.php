<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_folder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('job_id');
            $table->string('name');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('personal_jobs')->onDelete('cascade');
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
        Schema::dropIfExists('share_folder');
    }
}
