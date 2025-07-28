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
        Schema::create('client_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('supportID')->nullable();
            $table->unsignedBigInteger('appID');
            $table->enum('status', ['BELUM DIPROSES', 'SEDANG DIPROSES', 'SELESAI', 'DITUTUP'])->default('BELUM DIPROSES');
            $table->string('title');
            $table->string('link')->nullable();
            $table->timestamps();
        });
        Schema::create('dev_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supportID');
            $table->unsignedBigInteger('devID')->nullable();
            $table->unsignedBigInteger('roleID');
            $table->enum('status', ['BELUM DIPROSES', 'SEDANG DIPROSES', 'SELESAI', 'DITUTUP'])->default('BELUM DIPROSES');
            $table->string('title');
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_tickets');
        Schema::dropIfExists('dev_tickets');
    }
};
