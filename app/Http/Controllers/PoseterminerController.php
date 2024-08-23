<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Poseterminer;
use App\Models\Mission; // Utilisez Client au lieu de Mission

class PoseterminerController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'mission_id' => 'required|exists:clients,id', // Modification pour correspondre à la table clients
                'photo_emplacement_evaporateur' => 'required|image|max:2560',
                'photo_numero_serie_evaporateur' => 'required|image|max:2560',
                'photo_raccordement_electrique' => 'required|image|max:2560',
                'photo_emplacement_condensateur' => 'required|image|max:2560',
                'photo_numero_serie_condensateur' => 'required|image|max:2560',
            ]);

            $data = $request->all();
            $mission_id = $data['mission_id'];

            $photos = [
                'photo_emplacement_evaporateur', 
                'photo_numero_serie_evaporateur', 
                'photo_raccordement_electrique', 
                'photo_emplacement_condensateur', 
                'photo_numero_serie_condensateur'
            ];

            // Traitement des fichiers et stockage
            foreach ($photos as $photo) {
                if ($request->hasFile($photo)) {
                    $path = $request->file($photo)->store('photos', 'public');
                    $data[$photo] = $path;
                    
                    // Log pour débogage
                    Log::info("Fichier $photo stocké avec succès.", ['path' => $path]);
                } else {
                    // Log pour débogage en cas de fichier manquant
                    Log::error("Le fichier $photo est manquant.");
                }
            }

            // Vérifier si la mission existe déjà dans la table poseterminer
            $record = Poseterminer::where('mission_id', $mission_id)->first();

            if ($record) {
                // Mise à jour si l'enregistrement existe
                $record->update($data);
                Log::info("Enregistrement mis à jour dans poseterminer", ['record_id' => $record->id]);
            } else {
                // Création si l'enregistrement n'existe pas
                $record = Poseterminer::create($data);
                Log::info("Enregistrement créé dans poseterminer", ['record_id' => $record->id]);
            }

            // Mettre à jour l'attribut raisonsocial de la mission dans la table clients
            $client = Mission::find($mission_id); // Utiliser Client au lieu de Mission
            if ($client) {
                $client->raisonsocial = 1;
                $client->save();
                Log::info("Mise à jour de la mission avec raisonsocial = 1", ['client_id' => $mission_id]);
            } else {
                Log::error("Client avec ID $mission_id non trouvé.");
                return response()->json(['message' => 'Client non trouvé.'], 404);
            }

            // Préparer les liens vers les images
            $imageLinks = [];
            foreach ($photos as $photo) {
                if (isset($data[$photo])) {
                    $imageLinks[$photo] = Storage::url($data[$photo]);
                }
            }

            // Retourner la vue Blade avec les images
            return view('poseterminer-success', ['images' => $imageLinks]);

        } catch (\Exception $e) {
            // Gestion des exceptions et log de l'erreur
            Log::error("Erreur dans la méthode store", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Une erreur est survenue lors de l\'enregistrement des photos.'], 500);
        }
    }
}

