<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reachus_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('contact_method', 20);
            $table->string('contact_value');
            $table->text('reason');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['read_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reachus_messages');
    }
};
