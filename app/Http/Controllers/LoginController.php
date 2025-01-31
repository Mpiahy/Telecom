<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use App\Models\User;

class LoginController extends Controller
{
    // Load Login View
    public function loginView()
    {
        return view("auth.login");
    }
    
    // Login validate
    public function loginCheck(LoginRequest $request)
    {
        // Extraire les credentials après validation
        $credentials = $request->validated();
    
        // Identifier si l'input est un email ou un login
        $fieldType = filter_var($credentials['identifiant'], FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
    
        // Construire les données de connexion
        $credentials = [
            $fieldType => $credentials['identifiant'],
            'password' => $credentials['password']
        ];
    
        // Tentative de connexion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
    
            // Stocker le login dans la session
            $request->session()->put('login', $user->login);
            
            // Enregistrer le statut d'administrateur ou invité
            $status = $user->isAdmin ? 'admin' : 'invité';
            $request->session()->put('login_status', $status);
    
            return redirect()->route('index');
        }
    
        // Si l'authentification échoue
        return back()->withErrors(['identifiant' => "Identifiant ou mot de passe invalide"])
                     ->withInput();
    }    

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route("auth.login");
    }

    // Settings
    public function settingsView()
    {
        return view('auth.settings');
    }

    public function updateUser(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'login_usr' => 'required|string|max:255|unique:users,login,' . Auth::id(),
            'email_usr' => 'required|email|unique:users,email,' . Auth::id(),
            'nom_usr' => 'required|string|max:255',
            'prenom_usr' => 'required|string|max:255',
        ]);

        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Les champs contiennent des erreurs.'); // Toast
        }

        $user = User::find(Auth::id()); // Charge l'utilisateur avec son ID

        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        $result = $user->updateUserDetails($request->all());

        // Gérer le retour de la méthode du modèle
        if ($result === true) {
            return redirect()->back()->with('success', 'Vos informations ont été mises à jour avec succès.');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $result);
        }
    }

    public function updatePassword(Request $request)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'older_pwd' => 'required|string|min:8',
            'new_pwd' => 'required|string|min:8|confirmed',
        ], [
            'new_pwd.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'new_pwd.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);
    
        // Ajouter une règle pour vérifier que `new_pwd` est différent de `older_pwd`
        $validator->after(function ($validator) use ($request) {
            if ($request->input('new_pwd') === $request->input('older_pwd')) {
                $validator->errors()->add('new_pwd', 'Le nouveau mot de passe ne peut pas être identique à l\'ancien.');
            }
        });
    
        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Les champs contiennent des erreurs.');
        }
    
        // Récupérer l'utilisateur connecté
        $user = User::find(Auth::id());
    
        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }
    
        // Vérifier si l'ancien mot de passe est correct
        if (!Hash::check($request->input('older_pwd'), $user->password)) {
            return redirect()->back()->with('error', 'L\'ancien mot de passe est incorrect.');
        }
    
        // Appeler la méthode pour mettre à jour le mot de passe dans le modèle
        $result = $user->updatePassword($request->input('new_pwd'));
    
        if ($result === true) {
            return redirect()->back()->with('success', 'Votre mot de passe a été mis à jour avec succès.');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $result);
        }
    }
    
}
