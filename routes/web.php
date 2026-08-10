<?php

use Illuminate\Support\Facades\Route;

// Le backend Laravel est 100% REST API pour l'application React.js déportée
Route::get('/', function () {
    return response()->json([
        'system' => 'API REST - Recensement National de Côte d\'Ivoire',
        'version' => '2.1',
        'status' => 'online',
        'documentation' => '/api/stats/globales'
    ]);
});
