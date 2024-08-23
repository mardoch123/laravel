<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'idclient',
        'image_path',
        'uploaded_at',
    ];

    // Relation inverse avec le modèle Climatiseur si nécessaire
    public function climatiseur()
    {
        return $this->belongsTo(Climatiseur::class, 'client_id', 'idclient');
    }
}
