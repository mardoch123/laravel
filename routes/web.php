<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PoseterminerController;

Route::get('/images/{filename}', [PoseterminerController::class, 'showImage'])->name('image.display');


// Route pour la page d'accueil
Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::post('/upload-images', function () {
    include public_path('upload.php'); // Inclut le fichier de traitement
});

Route::get('/success_page', function () {
    return view('success_page'); // Créez une vue dans resources/views/success_page.blade.php
});


Route::get('/missions/rejetees', [MissionController::class, 'showRejectedMissions'])->name('missions.rejetees');

// Routes accessibles sans authentification
Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
Route::get('/missions/{id}', [MissionController::class, 'show'])->name('missions.show');
Route::post('/missions/{id}/postuler', [MissionController::class, 'postuler'])->name('missions.postuler');

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/mes-missions', [MissionController::class, 'mesMissions'])->name('mes-missions');
    Route::get('/missions-disponibles', [MissionController::class, 'missions'])->name('missions.disponibles');
    
});

Route::middleware('auth')->get('/mes-missionss', [MissionController::class, 'mesMissionsZ'])->name('missions.mes');

Route::get('/missionsz/{id}', [MissionController::class, 'showZ'])->name('missionsz.show');

// web.php
Route::get('/image/{id}', [ImageController::class, 'show'])->name('image.show');

// Route pour enregistrer les photos
Route::post('/poseterminer', [PoseterminerController::class, 'store'])->name('poseterminer.store');


// Route pour mettre à jour l'attribut raisonsocial
Route::put('/missions/{id}/update-raisonsocial', [MissionController::class, 'updateRaisonsocial']);


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/imagesin', function () {
    return response()->file(public_path('imagesin.php'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});