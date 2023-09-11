<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareFolderFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_folder_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("share_folder_id");
            $table->longText("path");
            $table->string("extension");
            $table->unsignedBigInteger("type");
            $table->foreign("share_folder_id")->references("id")->on("share_folder")->onDelete('cascade');
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
        Schema::dropIfExists('share_folder_files');
    }
}
