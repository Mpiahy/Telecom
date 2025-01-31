<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

        // Générer un mot de passe aléatoire
        $newPassword = Str::random(8); 

        // Mettre à jour le mot de passe en base (haché)
        $user->update(['password' => Hash::make($newPassword)]);

        return response()->json(['message' => 'Mot de passe réinitialisé.', 'newPassword' => $newPassword]);
    }

}
