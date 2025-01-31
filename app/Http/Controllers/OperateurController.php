<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ContactOperateur;

class OperateurController extends Controller
{

    // Load utilisateur View
    public function operateurView()
    {
        $login = Session::get('login');

        // Charge les opérateurs avec leurs contacts associés
        $contactsOperateurs = ContactOperateur::with('operateur')->get();

        return view('ref.operateur', compact('login','contactsOperateurs'));
    }

    public function modifierOperateur(Request $request)
    {
        $contact = ContactOperateur::findOrFail($request->id_contact);
    
        $contact->updateContact($request->only('nom_contact', 'email_contact'));
    
        return redirect()->route('ref.operateur')->with('success', 'Contact mis à jour avec succès.');
    }    

}
