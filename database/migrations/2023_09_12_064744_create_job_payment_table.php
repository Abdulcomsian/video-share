<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('client_transfer_status');
            $table->unsignedBigInteger('editor_transfer_status');
            $table->date('client_payment_date')->nullable();
            $table->date('editor_payment_date')->nullable();
            $table->foreign('job_id')->references('id')->on('personal_jobs')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
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
        Schema::dropIfExists('job_payment');
    }
}
