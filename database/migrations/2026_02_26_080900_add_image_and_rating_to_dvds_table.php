<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('dvds', function (Blueprint $table) {
            $table->string('image_url')->nullable();
            $table->integer('note')->default(0); // Note sur 5
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dvds', function (Blueprint $table) {
            //
        });
    }
};
