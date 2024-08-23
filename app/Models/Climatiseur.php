<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Climatiseur extends Model
{
    use HasFactory;

    protected $table = 'climatiseurs';

    protected $fillable = [
        'client_id',
        'marque',
        'puissance',
        'flexible',
        'installation_type',
        'prix_unitaire',
        'quantite',
        'entretien',
        'disjoncteur',
        'prix_net_apres_prime',
        'ttc',
        'nbreetage',
        'wifi',
        'carotage',
    ];

    // Définir la relation avec les images
    public function images()
    {
        return $this->hasMany(Image::class, 'client_id', 'idclient');
    }
}
