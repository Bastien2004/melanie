<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dvd extends Model
{
    use HasFactory;

    // Champs que l'on peut remplir via un formulaire
    protected $fillable = ['titre', 'realisateur', 'genre', 'annee', 'duree', 'image_url', 'note'];
}
