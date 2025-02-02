<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
class AdminController extends Controller
{
    public function manageView()
    {
        return view("auth.manage", ['accounts' => User::all()]);
    }

    /**
     * Inverser le type d'un utilisateur (Admin <-> Invité)
     */
    public function toggleType($id)
    {
        $user = User::find($id);

        if (!$user) {
            return Response::json(['error' => 'Utilisateur introuvable.'], 404);
        }

        // Vérifier si l'utilisateur connecté essaie de modifier son propre type
        if (Auth::id() == $user->id) {
            return Response::json(['error' => 'Vous ne pouvez pas modifier votre propre accès.'], 403);
        }

        // Basculer entre Admin et Invité
        $user->isAdmin = !$user->isAdmin;
        $user->save();

        return Response::json(['message' => 'Type modifié avec succès.', 'newType' => $user->isAdmin ? 'Admin' : 'Invité']);
    }

    /**
     * Désactiver un compte (ici, on le supprime, mais on peut aussi ajouter une colonne 'isActive')
     */
    public function disableAccount($id)
    {
        $user = User::find($id);

        if (!$user) {
            return Response::json(['error' => 'Utilisateur introuvable.'], 404);
        }

        $user->delete(); // Suppression de l'utilisateur

        return Response::json(['message' => 'Compte désactivé avec succès.']);
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'Utilisateur introuvable.'], 404);
        }
    
        // Générer un nouveau mot de passe temporaire
        $newPassword = Str::random(8) . "++";
    
        // Mettre à jour en base
        $user->update([
            'password' => Hash::make($newPassword),
            'temp_password' => $newPassword
        ]);
    
        return response()->json([
            'message' => 'Mot de passe réinitialisé.',
            'newPassword' => $newPassword,
            'userId' => $user->id
        ]);
    }    

    public function createAccount(Request $request)
    {
        $request->validate([
            'nom_usr' => 'required|string|max:255',
            'prenom_usr' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login',
            'email' => 'required|email|max:255|unique:users,email',
            'isAdmin' => 'required|boolean',
            'temp_password' => 'required|string|min:8'
        ]);

        $newUser = User::create([
            'nom_usr' => $request->nom_usr,
            'prenom_usr' => $request->prenom_usr,
            'login' => $request->login,
            'email' => $request->email,
            'password' => Hash::make($request->temp_password),
            'temp_password' => $request->temp_password,
            'isAdmin' => $request->isAdmin,
        ]);

        return response()->json(['message' => 'Compte créé avec succès!', 'user' => $newUser]);
    }

}
