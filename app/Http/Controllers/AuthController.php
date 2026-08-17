<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// Ce code sert à gérer l'authentification et l'enregistrement des utilisateurs.
// Il fonctionne avec le modèle User, les façades Auth, Hash et Validator de Laravel Sanctum.
// Dans le but d'authentifier les citoyens et les administrateurs, de générer des jetons d'accès et d'expédier les SMS OTP.
// Pour régler la vérification d'identité et l'accès sécurisé aux API du recensement.
class AuthController extends Controller
{
    // Ce code sert à connecter un utilisateur citoyen ou administrateur.
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
        // Nettoie le numéro de téléphone pour ne conserver que les chiffres
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

        // Évalue le rôle administrateur ou citoyen selon l'email, le nom ou le portail sélectionné
        $isAdmin = (str_contains(strtolower($user->email), 'admin') || str_contains(strtolower($user->name), 'agent') || str_contains(strtolower($user->name), 'admin') || $request->portal === 'admin');
        
        if ($isAdmin) {
            $user->role = 'admin';
            if (!$user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }
        } else {
            $user->role = 'citoyen';
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Connexion réussie au Portail National.',
            'data'    => [
                'user'  => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'telephone'        => $request->telephone ?? $identifier,
                    'role'             => $user->role,
                    'roles_list'       => $user->getRoleNames(),
                    'permissions_list' => $user->getAllPermissions()->pluck('name'),
                ],
                'token' => $token,
            ]
        ]);
    }

    // Simulation de la génération et de l'envoi d'un code OTP par SMS
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

    // Inscription d'un nouveau citoyen
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

        $user->assignRole(\App\Enums\RoleEnum::RESSORTISSANT->value);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Votre compte citoyen a été créé avec succès !',
            'data'    => [
                'user'  => [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'telephone'        => $request->telephone,
                    'role'             => 'citoyen',
                    'roles_list'       => $user->getRoleNames(),
                    'permissions_list' => $user->getAllPermissions()->pluck('name'),
                ],
                'token' => $token,
            ]
        ], 201);
    }

    // Récupération du profil de l'utilisateur actuellement connecté
    public function me(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->load('ressortissant');
            $user->roles_list = $user->getRoleNames();
            $user->permissions_list = $user->getAllPermissions()->pluck('name');
        }
        return response()->json([
            'status' => 'success',
            'data'   => $user
        ]);
    }

    // Déconnexion de l'utilisateur
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
