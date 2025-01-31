<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ContactOperateur;
use App\Models\TypeLigne;
use App\Models\Forfait;
use App\Models\Ligne;
use App\Models\StatutLigne;
use App\Models\Utilisateur;
use App\Models\Affectation;
use Illuminate\Validation\Rule;

class LigneController extends Controller
{
    // Load ligne View
    public function ligneView(Request $request)
    {
        $login = Session::get('login');
    
        $contactsOperateurs = ContactOperateur::with('operateur')->get();
        $types = TypeLigne::getLignesTypes();
        $forfaits = Forfait::all();
        $statuts = StatutLigne::all();
        $utilisateurs = Utilisateur::all();
    
        // Vérifier si le bouton "Tout" a été cliqué
        if ($request->has('reset_filters') && $request->input('reset_filters') == 'reset') {
            // Réinitialiser tous les filtres
            $filters = [
                'statut' => null,
                'type' => null,
                'search_ligne_num' => null,
                'search_ligne_sim' => null,
                'search_ligne_user' => null,
            ];
        } else {
            // Sinon, appliquer les filtres existants
            $filters = [
                'statut' => $request->input('statut'),
                'type' => $request->input('type'),
                'search_ligne_num' => $request->input('search_ligne_num'),
                'search_ligne_sim' => $request->input('search_ligne_sim'),
                'search_ligne_user' => $request->input('search_ligne_user'),
            ];
        }
    
        $lignes = Ligne::getLignesWithDetails($filters, "10"); // Ajout du paramètre pour la pagination
    
        return view('ref.ligne', compact('login', 'lignes', 'contactsOperateurs', 'types', 'forfaits', 'statuts', 'utilisateurs'));
    }    

    // Demander l'activation
    public function saveLigne(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'act_sim' => 'required|integer|unique:ligne,num_sim',
                'act_operateur' => 'required|exists:contact_operateur,id_operateur',
                'act_type' => 'required|exists:type_ligne,id_type_ligne',
                'act_forfait' => 'required|exists:forfait,id_forfait',
            ]);

            Ligne::createLigneWithDetails($validatedData);

            return redirect()
                ->route('ref.ligne')
                ->with('success', 'Ligne ajoutée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors($e->errors(), 'act_ligne_errors')
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors(['error' => 'Une erreur inattendue est survenue: ' . $e->getMessage()], 'act_ligne_errors')
                ->withInput();
        }
    }

    // Demander l'activation
    public function reactLigne(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'react_ligne_id' => 'required|exists:ligne,id_ligne', // Vérifie que la ligne existe dans la table "ligne"
                'react_sim' => 'required|integer', // Enlever la contrainte "unique" ici
                'react_operateur' => 'required|exists:contact_operateur,id_operateur', // Vérifie que l'opérateur existe
                'react_type' => 'required|exists:type_ligne,id_type_ligne', // Vérifie que le type de ligne existe
                'react_forfait' => 'required|exists:forfait,id_forfait', // Vérifie que le forfait existe
            ]);            

            Ligne::reactLigne($validatedData['react_ligne_id'],$validatedData);

            return redirect()
                ->route('ref.ligne')
                ->with('success', 'Ligne réactivée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors($e->errors(), 'react_ligne_errors')
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors(['error' => 'Une erreur inattendue est survenue: ' . $e->getMessage()], 'react_ligne_errors')
                ->withInput();
        }
    }

    // Enregistrer ligne(num d'appel) & Attribuer vers Utilisateur
    public function enrLigne(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'enr_ligne' => 'required|string|max:15', //03xxxxxxxx
                'enr_date' => 'required|date',
                'enr_id_ligne' => 'required|integer|exists:ligne,id_ligne', //id_ligne correspondant
                'enr_id_forfait' => 'required|integer|exists:forfait,id_forfait', //id_forfait correspondant
                'enr_user' => 'nullable|integer|exists:utilisateur,id_utilisateur', // Nullable pour gérer la récupération depuis le champ caché
                'selected_user' => 'nullable|integer|exists:utilisateur,id_utilisateur', // Champ caché
            ]);

            // Si `enr_user` est vide, utilise `selected_user`
            $validatedData['enr_user'] = $validatedData['enr_user'] ?? $validatedData['selected_user'];

            if (!$validatedData['enr_user']) {
                return redirect()
                    ->route('ref.ligne')
                    ->withErrors(['enr_user' => 'Aucun utilisateur valide sélectionné.'], 'enr_ligne_errors')
                    ->withInput();
            }

            $idLigne = $validatedData['enr_id_ligne'];
            $idForfait = $validatedData['enr_id_forfait'];
            $ligne = Ligne::findOrFail($idLigne);
            Forfait::findOrFail($idForfait);

            $ligne->enrLigne($validatedData['enr_ligne']);

            Affectation::creerAffectation(
                $validatedData['enr_date'],
                $idLigne,
                $idForfait,
                $validatedData['enr_user']
            );

            return redirect()
                ->route('ref.ligne')
                ->with('success', 'Affectation créée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors($e->errors(), 'enr_ligne_errors')
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors(['error' => 'Une erreur inattendue est survenue: ' . $e->getMessage()], 'enr_ligne_errors')
                ->withInput();
        }
    }

    // Recherche utilisateur dans enrLigne
    public function searchUser(Request $request)
    {
        try {
            $term = $request->input('query');

            // Si aucun terme n'est fourni, retourner une réponse vide
            if (empty($term)) {
                return response()->json([], 200);
            }

            // Rechercher les utilisateurs correspondants
            $utilisateurs = Utilisateur::searchUser($term);

            return response()->json($utilisateurs, 200);
        } catch (Exception $e) {
            // En cas d'erreur, retourner un message d'erreur avec un code 500
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    // Voir plus ligne
    public function detailLigne($id_ligne)
    {
        // Appel de la méthode optimisée pour récupérer les détails de la ligne
        $lignesBig = Ligne::getLignesWithBigDetails($id_ligne);

        // Vérifie si aucun résultat n'a été trouvé
        if (empty($lignesBig)) {
            return response()->json(['error' => 'Détails de la ligne introuvables.'], 404);
        }

        // Retourne le premier résultat trouvé (si applicable)
        return response()->json($lignesBig[0]);
    }

    // Modifier Ligne
    public function edtLigne(Request $request)
    {
        try {
            $statutEdt = $request->input('edt_statut');

            $rules = [
                'edt_id_ligne' => 'required|exists:ligne,id_ligne',
                'edt_sim' => [
                    'required',
                    'integer',
                    Rule::unique('ligne', 'num_sim')->ignore($request->edt_id_ligne, 'id_ligne'),
                ],
                'edt_operateur' => 'required|exists:operateur,id_operateur',
                'edt_type' => 'required|exists:type_ligne,id_type_ligne',
                'edt_forfait' => 'required|exists:forfait,id_forfait',
            ];

            if ($statutEdt !== 'En attente') {
                $rules['edt_ligne'] = [
                    'required',
                    'string',
                    'max:15',
                    Rule::unique('ligne', 'num_ligne')->ignore($request->edt_id_ligne, 'id_ligne'),
                ];
                $rules['edt_date'] = 'required|date';
            }

            $validatedData = $request->validate($rules);

            Ligne::updateLigne($validatedData['edt_id_ligne'], $validatedData);

            // if date_affectation existe
            if (!empty($validatedData['edt_date'])) {
                Affectation::updateAffectation($validatedData['edt_id_ligne'], $validatedData['edt_date']);
            }

            return redirect()
                ->route('ref.ligne')
                ->with('success', 'Ligne mise à jour avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors($e->errors(), 'edt_ligne_errors')
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors(['error' => 'Une erreur inattendue est survenue: ' . $e->getMessage()], 'edt_ligne_errors')
                ->withInput();
        }
    }

    public function rslLigne(Request $request)
    {
        try {
            // Validation des données envoyées
            $validatedData = $request->validate([
                'resil_id_ligne' => 'required|exists:ligne,id_ligne',
                'resil_date' => 'required|date',
            ]);

            $idLigne = $validatedData['resil_id_ligne'];
            $dateResil = $validatedData['resil_date'];

            $ligne = Ligne::findOrFail($idLigne);
            $ligne->rslLigne($idLigne);
            
            Affectation::rslAffectation($idLigne, $dateResil);

            return redirect()
                ->route('ref.ligne')
                ->with('success', 'La ligne a été résiliée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors($e->errors(), 'rsl_ligne_errors')
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.ligne')
                ->withErrors(['error' => 'Une erreur inattendue est survenue: ' . $e->getMessage()], 'rsl_ligne_errors')
                ->withInput();
        }
    }

    // Historique de la ligne
    public function histoLigne($id_ligne)
    {
        $histoLigne = Ligne::getHistoriqueLigne($id_ligne);

        // Retourne un tableau vide si aucun historique n'est trouvé
        if (empty($histoLigne)) {
            return response()->json([]);
        }

        return response()->json($histoLigne);
    }

}