<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticketID');
            $table->enum('type', ['client','support']);
            $table->longText('response')->nullable();
            $table->enum('check', ['sending', 'sent', 'reached', 'read'])->default('sending');
            $table->timestamps();
        });
        Schema::create('dev_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticketID');
            $table->enum('type', ['developer','support']);
            $table->longText('response')->nullable();
            $table->enum('check', ['sending', 'sent', 'reached', 'read'])->default('sending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_chats');
        Schema::dropIfExists('dev_chats');
    }
};
