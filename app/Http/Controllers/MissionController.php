<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Mission; // Assurez-vous que cette ligne est présente
use App\Models\Poseur;  // Assurez-vous que cette ligne est présente
use Illuminate\Support\Facades\Mail;
use App\Mail\MissionPostule;
use App\Models\Climatiseur; // Assurez-vous que cette ligne est présente
use App\Models\Image; // Assurez-vous que cette ligne est présente
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MissionController extends Controller
{
    public function mesMissions()
    {
        return Inertia::render('MesMissions');
    }

    public function missions()
    {
        return Inertia::render('Missions');
    }

    public function missionsRejeter()
    {
        return Inertia::render('MissionsRejeter');
    }

    public function show($id)
    {
        try {
            // Trouver le climatiseur par ID
            $climatiseur = Climatiseur::findOrFail($id);

            // Utiliser le client_id du climatiseur pour trouver toutes les entrées de climatiseurs et images associées
            $client_id = $climatiseur->client_id;

            // Récupérer toutes les entrées de climatiseurs pour ce client_id
            $climatiseurs = Climatiseur::where('client_id', $client_id)->get();

            // Récupérer toutes les images pour ce client_id
            $images = Image::where('idclient', $client_id)->get();

            // Construire les URLs d'images
            $baseLink = 'https://commercial.ecoagir-appli.com/devis/';
            $clientCommerce = $climatiseur->client->commerce ?? ''; // Ajuster selon votre modèle

            $formattedImages = $images->map(function ($image) use ($baseLink, $clientCommerce) {
                $imagePath = htmlspecialchars($image->image_path);
                $link = $baseLink . $imagePath;

                if ($clientCommerce === 'AIR') {
                    $link = str_replace('/devis/', '/devisair/', $link);
                }

                return [
                    'image_path' => $link
                ];
            });

            // Compter le nombre total de climatiseurs
            $totalClimatiseurs = $climatiseurs->count();

            return response()->json([
                'totalClimatiseurs' => $totalClimatiseurs,
                'climatiseurs' => $climatiseurs,
                'images' => $formattedImages
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans la méthode show: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }
    

    public function postuler(Request $request, $id)
    {
        // Récupérer l'utilisateur actuellement connecté
        $poseur = auth()->user();

        if ($poseur) {
            // Trouver le client avec l'ID fourni
            $client = Mission::find($id);
            
            if ($client) {
                // Mettre à jour l'attribut 'attributerpose'
                $client->attributerpose = $poseur->email;
                $client->save();
        
                // Rediriger avec un message de succès
                return redirect()->back()->with('success', 'Votre candidature a été envoyée.');
            }
        
            // Rediriger avec un message d'erreur si le client n'est pas trouvé
            return redirect()->back()->with('error', 'Client non trouvé.');
        }
        
        // Rediriger avec un message d'erreur si l'utilisateur n'est pas trouvé
        return redirect()->back()->with('error', 'Erreur lors de la candidature.');
    }

    public function showZ($id)
    {
        // Trouver la mission par ID
        $mission = Mission::find($id);
    
        // Vérifier si la mission existe
        if (!$mission) {
            abort(404, 'Mission non trouvée');
        }
    
        // Récupérer les détails des climatiseurs associés à la mission
        $climatiseurs = Climatiseur::where('client_id', $id)->get();
    
        // Récupérer les images associées à la mission
        $images = Image::where('idclient', $id)->get();
    
        // Construire les URLs d'images
$baseLink = 'https://commercial.ecoagir-appli.com/';
$clientCommerce = $climatiseurs->first()->client->commerce ?? ''; // Ajuster selon votre modèle

// Fonction pour vérifier la disponibilité d'un répertoire
function checkDirectoryExists($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

// Déterminer le répertoire accessible
$devisUrl = $baseLink . 'devis/';
$devisAirUrl = $baseLink . 'devisair/';
$directoryUrl = checkDirectoryExists($devisUrl) ? $devisUrl : $devisAirUrl;

$formattedImages = $images->map(function ($image) use ($directoryUrl) {
    $imagePath = htmlspecialchars($image->image_path);
    $link = $directoryUrl . $imagePath;

    return [
        'image_path' => $link
    ];
});

    
        // Retourner les détails via Inertia
        return inertia('MissionDetails', [
            'mission' => $mission,
            'climatiseurs' => $climatiseurs,
            'images' => $formattedImages,
        ]);
    }
    


    public function index()
    {
        $missions = Mission::all();
        return view('mission_table', compact('missions'));
        
    }

    public function mesMissionsZ()
{
    // Récupérer l'email de l'utilisateur connecté
    $userEmail = auth()->user()->email;

    // Filtrer les missions où l'email est à la fois dans attributerpose et poseurs
    $missions = Mission::where('attributerpose', $userEmail)
        ->where('poseurs', $userEmail)
        ->get();

    // Log les missions pour vérifier
    logger()->info('Missions pour l\'utilisateur : ' . $userEmail, $missions->toArray());

    // Retourner les missions via Inertia
    return inertia('MesMissions', [
        'missions' => $missions,
    ]);
}

    


public function showRejectedMissions()
{
    // Récupère l'email de l'utilisateur actif
    $userEmail = auth()->user()->email;

    // Récupère les missions qui répondent aux critères spécifiés
    $missions = Mission::where('statut', 1)
    ->where(function ($query) use ($userEmail) {
        $query->where('attributerpose', '=', $userEmail)
            ->where('poseurs', '!=', $userEmail);
    })
    ->get();


    // Vérifie s'il y a des missions qui correspondent
    if ($missions->isEmpty()) {
        return Inertia::render('Missions/Rejetees', [
            'missions' => [],
            'csrfToken' => csrf_token(),
            'message' => 'Aucune mission rejetée trouvée.'
        ]);
    }

    return Inertia::render('Missions/Rejetees', [
        'missions' => $missions,
        'csrfToken' => csrf_token(),
    ]);
}

public function updateRaisonsocial($id)
{
    $mission = Mission::find($id);
    if ($mission) {
        $mission->raisonsocial = 1;
        $mission->save();
        return response()->json(['message' => 'Attribut raisonsocial mis à jour avec succès.']);
    }
    return response()->json(['message' => 'Mission non trouvée.'], 404);
}
}