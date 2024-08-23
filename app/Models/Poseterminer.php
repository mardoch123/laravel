<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poseterminer extends Model
{
    // Spécifiez la table si elle ne suit pas les conventions de nommage de Laravel
    protected $table = 'poseterminer'; // Utilisez le nom de la table dans votre base de données

    // Les attributs que vous pouvez remplir
    protected $fillable = [
        'mission_id',
        'photo_emplacement_evaporateur',
        'photo_numero_serie_evaporateur',
        'photo_raccordement_electrique',
        'photo_emplacement_condensateur',
        'photo_numero_serie_condensateur',
    ];
}
