<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Queue;
use App\Models\Poseterminer;
use App\Models\Mission;
use App\Jobs\ProcessImage;

class PoseterminerController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'mission_id' => 'required|exists:clients,id',
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

            // Créer le dossier 'uploads' dans 'public' si nécessaire
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }

            foreach ($photos as $photo) {
                if ($request->hasFile($photo)) {
                    // Générer un nom unique pour chaque fichier
                    $photoName = Str::random(10) . '.' . $request->file($photo)->getClientOriginalExtension();
                    $file = $request->file($photo);

                    // Stocker le fichier sans redimensionner pour un traitement asynchrone
                    $path = $file->move(public_path('uploads'), $photoName);
                    $data[$photo] = 'uploads/' . $photoName;

                    // Placer le traitement en file d'attente pour redimensionnement et compression
                    Queue::push(new ProcessImage($data[$photo], 800, 600, 60)); // Taille cible et qualité

                    Log::info("Fichier $photo en cours de traitement en arrière-plan.", ['path' => $data[$photo]]);
                } else {
                    Log::error("Le fichier $photo est manquant.");
                }
            }

            // Vérifier si la mission existe déjà dans la table poseterminer
            $record = Poseterminer::where('mission_id', $mission_id)->first();

            if ($record) {
                $record->update($data);
                Log::info("Enregistrement mis à jour dans poseterminer", ['record_id' => $record->id]);
            } else {
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

            // Préparer les liens vers les images
            $imageLinks = [];
            foreach ($photos as $photo) {
                if (isset($data[$photo])) {
                    $imageLinks[$photo] = url($data[$photo]);
                }
            }

            return view('poseterminer-success', ['images' => $imageLinks]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return view('error', ['errors' => $e->errors()]);
        } catch (\Exception $e) {
            Log::error("Erreur dans la méthode store", ['error' => $e->getMessage()]);
            return view('error', ['errors' => ['Une erreur est survenue lors de l\'enregistrement des photos.']]);
        }
    }
}

