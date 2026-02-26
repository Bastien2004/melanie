<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livres', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('auteur');
            $table->string('genre')->nullable();
            $table->integer('annee')->nullable();
            $table->string('maisonEdition')->nullable();
            $table->integer('nbPage')->nullable();
            $table->string('format')->nullable();
            $table->string('image')->nullable();
            $table->text('avis')->nullable();
            $table->integer('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
