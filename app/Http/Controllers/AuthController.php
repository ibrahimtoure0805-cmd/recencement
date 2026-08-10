<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Authentification par Numéro de Téléphone (ou Email) & Mot de passe.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telephone' => 'required_without:email|string',
            'email'     => 'nullable|string',
            'password'  => 'required|string',
            'portal'    => 'nullable|string|in:citoyen,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Veuillez saisir vos identifiants et votre mot de passe.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $identifier = $request->telephone ?? $request->email;
        $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
        $generatedEmail = $cleanPhone ? $cleanPhone . '@recensement.ci' : '';

        // Recherche du compte utilisateur existant
        $user = User::where('email', $identifier)
                    ->when($generatedEmail, function($q) use ($generatedEmail) {
                        return $q->orWhere('email', $generatedEmail);
                    })
                    ->orWhere('name', 'LIKE', '%' . $identifier . '%')
                    ->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucun compte citoyen trouvé avec ce numéro. Veuillez d\'abord vous inscrire.'
            ], 404);
        }

        // Vérification du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mot de passe incorrect. Veuillez réanalyser votre saisie.'
            ], 401);
        }
        $user->role = (str_contains(strtolower($user->email), 'admin') || str_contains(strtolower($user->name), 'agent') || str_contains(strtolower($user->name), 'admin') || $request->portal === 'admin') ? 'admin' : 'citoyen';

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Connexion réussie au Portail National.',
            'data'    => [
                'user'  => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'telephone' => $request->telephone ?? $identifier,
                    'role'      => $user->role,
                ],
                'token' => $token,
            ]
        ]);
    }

    /**
     * Génère et simule l'envoi d'un code OTP SMS pour la validation du numéro de téléphone.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telephone' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Numéro de téléphone invalide.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $request->telephone);
        $emailGenerated = $cleanPhone . '@recensement.ci';

        $existingUser = User::where('email', $emailGenerated)->first();
        if ($existingUser) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Un compte citoyen existe déjà avec ce numéro de téléphone. Veuillez vous connecter.'
            ], 409);
        }

        // Génération d'un code OTP à 4 chiffres
        $otp = (string) rand(1000, 9999);

        return response()->json([
            'status'  => 'success',
            'message' => 'Code OTP transmis par SMS avec succès.',
            'data'    => [
                'telephone' => $request->telephone,
                'otp'       => $otp,
            ]
        ]);
    }

    /**
     * Inscription Citoyenne (Nom Complet, Numéro de téléphone, Créer un mot de passe).
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'telephone' => 'required|string|max:30',
            'password'  => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Formulaire d\'inscription invalide. Vérifiez vos informations.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $request->telephone);
        $emailGenerated = $cleanPhone . '@recensement.ci';

        // Vérifier si un compte avec ce téléphone existe déjà
        $existingUser = User::where('email', $emailGenerated)->first();
        if ($existingUser) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Un compte citoyen existe déjà avec ce numéro de téléphone. Veuillez vous connecter.'
            ], 409);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $emailGenerated,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Votre compte citoyen a été créé avec succès !',
            'data'    => [
                'user'  => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'telephone' => $request->telephone,
                    'role'      => 'citoyen',
                ],
                'token' => $token,
            ]
        ], 201);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->role = (str_contains(strtolower($user->email), 'admin') || str_contains(strtolower($user->name), 'agent') || str_contains(strtolower($user->name), 'admin')) ? 'admin' : 'citoyen';
            
            if (empty($user->telephone) && str_contains((string)$user->email, '@recensement.ci')) {
                $cleanPhone = preg_replace('/[^0-9]/', '', explode('@', (string)$user->email)[0] ?? '');
                if ($cleanPhone) {
                    $user->telephone = $cleanPhone;
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'data'   => $user
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Déconnexion réussie.'
        ]);
    }
}
