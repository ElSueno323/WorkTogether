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
        Schema::dropIfExists('participations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_task');
            $table->integer('id_user');
            $table->timestamps();
        });
    }
};
