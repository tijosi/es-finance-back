<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('destinatarios', function (Blueprint $table) {
            $table->id();
            $table->text('nome');
            $table->create();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinatarios', function (Blueprint $table) {
            $table->delete();
        });

    }
};
