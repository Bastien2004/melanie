<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $fillable = [
        'titre',
        'auteur',
        'genre',
        'annee',
        'maisonEdition',
        'nbPage',
        'format',
        'image',
        'avis',
        'note'
    ];
}
