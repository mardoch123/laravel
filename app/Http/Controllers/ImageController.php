<?php

namespace App\Http\Controllers;

use App\Models\Image; // Assure-toi que ce modèle existe
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Affiche l'image en grand.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Recherche l'image par son ID
        $image = Image::findOrFail($id);
        
        // Retourne la vue avec l'image
        return view('image.show', compact('image'));
    }
}
