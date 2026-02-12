<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSocialAuthColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firebase_uid')->nullable()->unique()->after('email');
            $table->string('login_provider')->nullable()->after('firebase_uid');
        });

        // Make password nullable using raw SQL (avoids doctrine/dbal dependency)
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firebase_uid', 'login_provider']);
        });

        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
    }
}
