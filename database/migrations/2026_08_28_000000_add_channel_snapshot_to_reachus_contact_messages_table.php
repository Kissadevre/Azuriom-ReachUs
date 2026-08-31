<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reachus_contact_messages', function (Blueprint $table) {
            $table->string('contact_channel_name', 64)->after('contact_method');
            $table->string('contact_channel_icon', 64)->after('contact_channel_name');
        });
    }

    public function down(): void
    {
        Schema::table('reachus_contact_messages', function (Blueprint $table) {
            $table->dropColumn(['contact_channel_name', 'contact_channel_icon']);
        });
    }
};
