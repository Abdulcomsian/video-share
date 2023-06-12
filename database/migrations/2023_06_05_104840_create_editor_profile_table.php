<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditorProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("editor_id");
            $table->string('title');
            $table->longText('bio');
            $table->string('service_offering');
            $table->double('amount_per_hour' , 8 , 2)->nullable();
            $table->foreign('editor_id')->references('id')->on('users');
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
        Schema::dropIfExists('editor_profile');
    }
}
