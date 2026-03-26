<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_payment', function (Blueprint $table) {
            $table->decimal('bid_amount', 10, 2)->nullable()->after('payment_intent_id');
            $table->decimal('platform_fee', 10, 2)->nullable()->after('bid_amount');
            $table->string('stripe_transfer_id')->nullable()->after('platform_fee');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('stripe_account_id');
        });
    }

    public function down(): void
    {
        Schema::table('job_payment', function (Blueprint $table) {
            $table->dropColumn(['bid_amount', 'platform_fee', 'stripe_transfer_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
        });
    }
};
