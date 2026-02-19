<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeCountryAndCityNullableInAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE address MODIFY country_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE address MODIFY city_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE address MODIFY country_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE address MODIFY city_id BIGINT UNSIGNED NOT NULL');
    }
}
