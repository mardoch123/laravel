<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    // Spécifiez le nom de la table si ce n'est pas le nom par défaut
    protected $table = 'clients';

    // Définissez les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'civilite', 'nom', 'prenom', 'adresse', 'code_postal', 'ville',
        'portable', 'domicile', 'email', 'date', 'agent_commercial',
        'observations', 'modalites_paiement', 'echeances', 'mensualites',
        'signature_client', 'signature_agent', 'nbremensualites',
        'identite', 'annegarentie', 'duree', 'passage', 'fonctionsignatire',
        'siren', 'raisonsocial', 'signer', 'statut', 'objetrefuser',
        'nbreetage', 'montantaccompte', 'signaturesend', 'demandenouveau',
        'heureenvoie', 'dateauto', 'installed', 'commerce', 'attributerpose' , 'poseurs', 'id'
    ];

    // Définissez les attributs qui devraient être traités comme des dates
    protected $dates = ['heureenvoie', 'dateauto'];

    // Définissez les attributs qui doivent être des booléens
    protected $casts = [
        'signer' => 'boolean',
        'statut' => 'boolean',
        'demandenouveau' => 'boolean',
    ];
}

