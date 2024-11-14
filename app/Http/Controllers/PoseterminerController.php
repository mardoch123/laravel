<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Poseterminer;
use App\Models\Mission;

class PoseterminerController extends Controller
{
    public function store(Request $request)
{
    try {
        // Log pour afficher les données reçues avant la validation
        Log::info("Données reçues dans store:", $request->all());

        // Validation des données
        $validatedData = $request->validate([
            'mission_id' => 'required|exists:clients,id', 
            'photo_emplacement_evaporateur' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'photo_numero_serie_evaporateur' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'photo_raccordement_electrique' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'photo_emplacement_condensateur' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'photo_numero_serie_condensateur' => 'required|file|mimes:jpeg,png,jpg|max:10240',
        ]);

        // Extraire les données
        $data = $request->all();
        $mission_id = $data['mission_id'];

        // Liste des champs photo
        $photos = [
            'photo_emplacement_evaporateur', 
            'photo_numero_serie_evaporateur', 
            'photo_raccordement_electrique', 
            'photo_emplacement_condensateur', 
            'photo_numero_serie_condensateur'
        ];

        // Stockage des fichiers dans storage/app/public/uploads
        foreach ($photos as $photo) {
            if ($request->hasFile($photo)) {
                // Générer un nom unique pour chaque fichier
                $photoName = Str::random(10) . '.' . $request->file($photo)->getClientOriginalExtension();

                // Stocker le fichier dans storage/app/public/uploads
                $path = $request->file($photo)->storeAs('uploads', $photoName, 'public');
                if (!$path) {
                    Log::error("Échec du stockage de l'image pour $photo.");
                    return response()->json(['message' => "Erreur de stockage pour $photo."], 500);
                }

                // Enregistrer le nom du fichier dans $data
                $data[$photo] = $photoName;  
                Log::info("Fichier $photo stocké avec succès.", ['filename' => $photoName]);
            } else {
                Log::warning("Le fichier $photo est manquant dans la requête.");
            }
        }

        // Vérifier si la mission existe déjà dans la table poseterminer
        $record = Poseterminer::where('mission_id', $mission_id)->first();

        if ($record) {
            // Mettre à jour l'enregistrement existant
            $record->update($data);
            Log::info("Enregistrement mis à jour dans poseterminer", ['record_id' => $record->id]);
        } else {
            // Créer un nouvel enregistrement
            $record = Poseterminer::create($data);
            Log::info("Enregistrement créé dans poseterminer", ['record_id' => $record->id]);
        }

        // Mettre à jour l'attribut raisonsocial de la mission dans la table clients
        $client = Mission::find($mission_id); 
        if ($client) {
            $client->raisonsocial = 1;
            $client->save();
            Log::info("Mise à jour de la mission avec raisonsocial = 1", ['client_id' => $mission_id]);
        } else {
            Log::error("Client avec ID $mission_id non trouvé.");
            return response()->json(['message' => 'Client non trouvé.'], 404);
        }

        // Préparer les liens des fichiers pour la réponse JSON
        $imageLinks = [];
        foreach ($photos as $photo) {
            if (isset($data[$photo])) {
                $imageLinks[$photo] = route('image.display', ['filename' => $data[$photo]]);
            }
        }

        // Retourner une réponse JSON pour un succès côté client
        return response()->json([
            'images' => $imageLinks,
            'message' => 'Enregistrement réussi'
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Retourner les erreurs de validation en JSON avec le code 422
        Log::error("Erreur de validation", ['errors' => $e->errors()]);
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        // Retourner une erreur serveur en cas d'exception
        Log::error("Erreur dans la méthode store", ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Une erreur est survenue lors de l\'enregistrement des photos.'], 500);
    }
}

    public function showImage($filename)
    {
        $path = storage_path('app/public/uploads/' . $filename);

        // Vérifiez si le fichier existe
        if (!file_exists($path)) {
            abort(404);
        }

        // Récupérer le contenu de l'image et le type MIME
        $file = file_get_contents($path);
        $type = mime_content_type($path);

        // Retourne la réponse avec le type MIME de l'image
        return response($file)->header('Content-Type', $type);
    }
}