<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtendDeliveryColumnsInPersonalJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_jobs', function (Blueprint $table) {
            $table->boolean('is_extend_delivery')->nullable()->after('awarded_date');
            $table->date('extended_delivery_date')->nullable()->after('is_extend_delivery');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_jobs', function (Blueprint $table) {
            $table->dropColumn('is_extend_delivery');
            $table->dropColumn('extended_delivery_date');
        });
    }
}
