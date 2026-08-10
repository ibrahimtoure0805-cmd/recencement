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
    // Il fonctionne avec les identifiants reçus (téléphone/email, mot de passe, type de portail).
    // Dans le but de vérifier le mot de passe hashé et de délivrer un jeton d'accès Sanctum.
    // Pour régler la connexion sécurisée sur la plateforme.
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

    // Ce code sert à simuler la génération et l'envoi d'un code OTP par SMS pour la vérification téléphonique.
    // Il fonctionne avec le numéro de téléphone transmis dans la requête HTTP.
    // Dans le but de fournir un code de confirmation aléatoire à 4 chiffres à l'utilisateur.
    // Pour régler la pré-validation du numéro de téléphone avant création du compte.
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

        // Nettoie le numéro de téléphone pour constituer l'identifiant email virtuel
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

    // Ce code sert à créer un nouveau compte citoyen.
    // Il fonctionne avec le nom, le numéro de téléphone et le mot de passe saisis.
    // Dans le but d'enregistrer l'utilisateur en base de données avec un mot de passe sécurisé et de générer un jeton Sanctum.
    // Pour régler l'auto-inscription des ressortissants.
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

        // Extrait uniquement les chiffres du numéro de téléphone pour composer l'identifiant système
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

    // Ce code sert à récupérer le profil de l'utilisateur actuellement connecté.
    // Il fonctionne avec le jeton Bearer de la requête HTTP Sanctum.
    // Dans le but de renvoyer l'objet utilisateur avec son rôle calculé et son numéro de téléphone nettoyé.
    // Pour régler le maintien de la session et la personnalisation de l'interface frontend.
    public function me(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // Détermine dynamiquement le rôle d'administration à partir de l'adresse email ou du nom
            $user->role = (str_contains(strtolower($user->email), 'admin') || str_contains(strtolower($user->name), 'agent') || str_contains(strtolower($user->name), 'admin')) ? 'admin' : 'citoyen';
            
            if (empty($user->telephone) && str_contains((string)$user->email, '@recensement.ci')) {
                // Reconstitue le téléphone depuis l'email si absent de l'attribut principal
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

    // Ce code sert à déconnecter l'utilisateur courant.
    // Il fonctionne avec le jeton d'accès actuellement actif de l'utilisateur authentifié.
    // Dans le but de révoquer et supprimer le jeton d'accès en base de données.
    // Pour régler la fermeture sécurisée de la session utilisateur.
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
