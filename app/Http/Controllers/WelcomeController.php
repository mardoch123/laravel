<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $clients = Client::all(); // Récupère toutes les données de la table 'clients'
        return Inertia::render('Welcome', ['clients' => $clients]);
    }
}
