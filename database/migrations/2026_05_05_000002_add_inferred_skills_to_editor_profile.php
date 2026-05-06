<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editor_profile', function (Blueprint $table) {
            // Reserved for future AI-inferred skills feature; nullable, no API surface yet
            $table->json('inferred_skills')->nullable()->after('amount_per_hour');
        });
    }

    public function down(): void
    {
        Schema::table('editor_profile', function (Blueprint $table) {
            $table->dropColumn('inferred_skills');
        });
    }
};
