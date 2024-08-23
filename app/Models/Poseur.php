<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poseur extends Model
{
    // Spécifie la table associée à ce modèle
    protected $table = 'clients'; // Assure-toi que le nom correspond à la table dans ta base de données

    // Les attributs que tu peux remplir en masse (mass assignment)
    protected $fillable = [
        'email', 
        'password', // Si tu as un mot de passe
        // Ajoute d'autres attributs si nécessaire
    ];

    // Si tu n'as pas de timestamps (created_at et updated_at), tu peux désactiver cette fonctionnalité
    // public $timestamps = false;

    // Optionnel : définis la clé primaire si ce n'est pas 'id'
    // protected $primaryKey = 'id';

    // Optionnel : définis le type de clé primaire si ce n'est pas 'int'
    // protected $keyType = 'string'; // Par exemple, si c'est une clé primaire de type string

    // Optionnel : définit si la clé primaire est auto-incrémentée
    // public $incrementing = false;
}

