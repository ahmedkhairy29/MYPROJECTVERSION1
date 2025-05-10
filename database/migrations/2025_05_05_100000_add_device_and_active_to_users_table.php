<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceAndActiveToUsersTable extends Migration

{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_active')->default(false)->after('email_verified_at');
        $table->string('activation_token')->nullable()->after('is_active');
        //$table->string('device_id')->nullable()->after('activation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'activation_token', 'device_id']);
        });
    }
}

