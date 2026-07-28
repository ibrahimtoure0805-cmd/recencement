<?php

use App\Http\Controllers\CantonController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RessortissantController;
use App\Http\Controllers\SousPrefectureController;
use App\Http\Controllers\TribuController;
use App\Http\Controllers\VillageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ce fichier sert à définir toutes les adresses (URL) que notre API accepte.
// Il fonctionne avec les contrôleurs : chaque adresse est reliée au contrôleur qui sait y répondre.
// Dans le but d'offrir un point d'entrée unique et organisé pour consulter ou gérer nos données.
// Pour régler la question « quelle adresse appeler pour obtenir quoi ? » : tout est listé ici.
// Toutes ces adresses commencent automatiquement par /api (ex. /api/districts).

// Renvoie les informations de la personne actuellement connectée.
// Seule adresse protégée pour l'instant : sans connexion valide, l'accès est refusé.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// --- Structure administrative ANStat ---
// Chaque ligne crée 2 adresses : une pour la liste complète, une pour un élément précis.
// Consultation uniquement (« only index + show ») : ces données viennent de l'ANStat,
// c'est la commande d'import qui les remplit — personne ne doit les modifier à la main.

Route::apiResource('districts', DistrictController::class)->only(['index', 'show']);
Route::apiResource('regions', RegionController::class)->only(['index', 'show']);
Route::apiResource('departements', DepartementController::class)->only(['index', 'show']);

// Les sous-préfectures sont renvoyées par paquets de 50 (526 au total, voir le contrôleur).
Route::apiResource('sous-prefectures', SousPrefectureController::class)->only(['index', 'show']);

// --- Structure coutumière ---
// Chaque ligne crée 5 adresses : lister, consulter un élément, ajouter, modifier, supprimer.
// Gestion complète car aucune source officielle n'existe pour ces données :
// elles ne peuvent être saisies et entretenues qu'à travers ces adresses.

Route::apiResource('cantons', CantonController::class);
Route::apiResource('tribus', TribuController::class);
Route::apiResource('villages', VillageController::class);

// --- Référentiel des Pays ---
// Renvoie la liste complète des 193 pays membres de l'ONU.
Route::get('/pays', [PaysController::class, 'index']);

// --- Ressortissants ---
// Gestion complète des fiches de recensement (identité, rattachements, résidence intégrée).

Route::apiResource('ressortissants', RessortissantController::class);
