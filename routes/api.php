<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CantonController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RessortissantController;
use App\Http\Controllers\SousPrefectureController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TribuController;
use App\Http\Controllers\VillageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- Authentification & Gestion des Rôles ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);

// --- Structure administrative ANStat (Consultation publique) ---
Route::apiResource('districts', DistrictController::class)->only(['index', 'show']);
Route::apiResource('regions', RegionController::class)->only(['index', 'show']);
Route::apiResource('departements', DepartementController::class)->only(['index', 'show']);
Route::apiResource('sous-prefectures', SousPrefectureController::class)->only(['index', 'show']);

// --- Structure coutumière (Consultation publique) ---
Route::get('/cantons', [CantonController::class, 'index']);
Route::get('/cantons/{canton}', [CantonController::class, 'show']);
Route::get('/tribus', [TribuController::class, 'index']);
Route::get('/tribus/{tribu}', [TribuController::class, 'show']);
Route::get('/villages', [VillageController::class, 'index']);
Route::get('/villages/{village}', [VillageController::class, 'show']);

// --- Référentiel des Pays ---
Route::get('/pays', [PaysController::class, 'index']);

// --- Inscription / Soumission publique d'une fiche citoyenne ---
Route::post('/ressortissants', [RessortissantController::class, 'store']);

// --- Espace Sécurisé (Authentification requise via Laravel Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    // Session utilisateur
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Fiche citoyenne propre de l'utilisateur connecté
    Route::get('/ressortissant/me', [RessortissantController::class, 'monDossier']);

    // Statistiques confidentielles (Reservées Super Admin)
    Route::middleware('can:stats:view')->prefix('stats')->group(function () {
        Route::get('/globales', [StatistiqueController::class, 'globales']);
        Route::get('/diaspora', [StatistiqueController::class, 'diaspora']);
        Route::get('/coutumier', [StatistiqueController::class, 'coutumier']);
    });

    // Modération & Consultation des Ressortissants
    Route::get('/ressortissants', [RessortissantController::class, 'index']);
    Route::get('/ressortissants/{ressortissant}', [RessortissantController::class, 'show']);

    // Console de Modération des Ressortissants
    Route::patch('/ressortissants/{ressortissant}/valider', [RessortissantController::class, 'valider']);
    Route::patch('/ressortissants/{ressortissant}/rejeter', [RessortissantController::class, 'rejeter']);
    Route::match(['put', 'post'], '/ressortissants/{ressortissant}', [RessortissantController::class, 'update']);
    Route::delete('/ressortissants/{ressortissant}', [RessortissantController::class, 'destroy']);

    // Modération / Écriture des structures coutumières
    Route::post('/cantons', [CantonController::class, 'store']);
    Route::put('/cantons/{canton}', [CantonController::class, 'update']);
    Route::delete('/cantons/{canton}', [CantonController::class, 'destroy']);

    Route::post('/tribus', [TribuController::class, 'store']);
    Route::put('/tribus/{tribu}', [TribuController::class, 'update']);
    Route::delete('/tribus/{tribu}', [TribuController::class, 'destroy']);

    Route::post('/villages', [VillageController::class, 'store']);
    Route::put('/villages/{village}', [VillageController::class, 'update']);
    Route::delete('/villages/{village}', [VillageController::class, 'destroy']);
});

