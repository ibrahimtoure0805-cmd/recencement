<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\JsonResponse;

class PaysController extends Controller
{
    /**
     * Renvoie la liste complète des 193 pays membres de l'ONU,
     * ordonnée avec la Côte d'Ivoire en premier (is_default = true),
     * puis l'ensemble par ordre alphabétique.
     */
    public function index(): JsonResponse
    {
        $pays = Pays::query()
            ->orderByDesc('is_default')
            ->orderBy('nom')
            ->get();

        return response()->json($pays);
    }
}
