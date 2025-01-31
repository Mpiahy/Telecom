<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Utilisateur;
use App\Models\TypeUtilisateur;
use App\Models\Fonction;
use App\Models\Ligne;
use App\Models\Localisation;
use App\Models\Operateur;
use App\Models\StatutEquipement;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Affichage de la vue user.blade.php
    public function userView(Request $request)
    {
        $login = Session::get('login');
        $types = TypeUtilisateur::all();
        $fonctions = Fonction::all();
        $chantiers = Localisation::all();
        $operateurs = Operateur::all();
    
        // Appliquer les filtres avec pagination
        $utilisateurs = Utilisateur::withTrashed()
            ->with(['typeUtilisateur', 'fonction', 'localisation'])
            ->filterByType($request->input('type'))
            ->filterByChantier($request->input('search_user_chantier'))
            ->filterByLogin($request->input('search_user_login'))
            ->filterByName($request->input('search_user_name'))
            ->orderBy('updated_at', 'desc')
            ->paginate(5);
    
        return view('ref.user', compact('login', 'types', 'fonctions', 'chantiers', 'utilisateurs', 'operateurs'));
    }    

    // Insertion d'un nouvel utilisateur
    public function ajouterUtilisateur(Request $request)
    {
        try {
            // Validation des champs de base
            $validatedData = $request->validate([
                'matricule_add' => 'nullable|unique:utilisateur,matricule',
                'nom_add' => 'required|string|max:50',
                'prenom_add' => 'required|string|max:50',
                'login_add' => 'required|string|max:40|unique:utilisateur,login',
                'id_type_utilisateur_add' => 'required|exists:type_utilisateur,id_type_utilisateur',
                'id_localisation_add' => 'required|exists:localisation,id_localisation',
            ]);
    
            // Gestion de la fonction
            if ($request->filled('new_fonction_add')) {
                // Valider le champ "new_fonction_add" s'il est rempli
                $request->validate([
                    'new_fonction_add' => 'required|string|max:100', // Ajout d'une limite de caractères
                ]);
    
                // Utilisation de la méthode du modèle pour créer une nouvelle fonction
                $fonction = Fonction::creerNouvelleFonction($request->new_fonction_add);
    
                // Assigner l'ID de la nouvelle fonction
                $validatedData['id_fonction_add'] = $fonction->id_fonction;
            } else {
                // Sinon, on utilise une fonction existante
                $request->validate([
                    'id_fonction_add' => 'required|exists:fonction,id_fonction',
                ]);
    
                $validatedData['id_fonction_add'] = $request->id_fonction_add;
            }
    
            // Création de l'utilisateur
            Utilisateur::ajouterUtilisateur($validatedData);
    
            return redirect()->route('ref.user')->with('success', __('Utilisateur ajouté avec succès !'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation avec les données du formulaire et rouvrir le modal
            return redirect()->route('ref.user')
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_with_error', 'modal_add_emp');
        }
    }    

    // Modification d'un utilisateur existant
    public function modifierUtilisateur(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'id_edt' => 'required|exists:utilisateur,id_utilisateur',
                'matricule_edt' => 'nullable', // Nullable pour éviter d'obliger la saisie
                'nom_edt' => 'required|string|max:255',
                'prenom_edt' => 'required|string|max:255',
                'login_edt' => 'required|string|max:255|unique:utilisateur,login,' . $request->id_edt . ',id_utilisateur',
                'id_type_utilisateur_edt' => 'required|exists:type_utilisateur,id_type_utilisateur',
                'id_localisation_edt' => 'required|exists:localisation,id_localisation',
            ]);

            // Gestion de la fonction
            if ($request->filled('new_fonction_edt')) {
                // Valider le champ "new_fonction_edt" s'il est rempli
                $request->validate([
                    'new_fonction_edt' => 'required|string|max:100', // Ajout d'une limite de caractères
                ]);
    
                // Utilisation de la méthode du modèle pour créer une nouvelle fonction
                $fonction = Fonction::creerNouvelleFonction($request->new_fonction_edt);
    
                // Assigner l'ID de la nouvelle fonction
                $validated['id_fonction_edt'] = $fonction->id_fonction;
            } else {
                // Sinon, on utilise une fonction existante
                $request->validate([
                    'id_fonction_edt' => 'required|exists:fonction,id_fonction',
                ]);
    
                $validated['id_fonction_edt'] = $request->id_fonction_edt;
            }

            Utilisateur::modifierUtilisateur($validated);

            return redirect()->route('ref.user')->with('success', __('Utilisateur modifié avec succès.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirection en cas d'erreurs de validation
            return redirect()->route('ref.user')
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_with_error', 'modal_edit_emp');
        }
    } 

    public function supprimerUtilisateur(Request $request, $id)
    {
        Log::info("Début de la suppression de l'utilisateur", ['id' => $id]);

        // Vérifier si l'utilisateur existe
        $utilisateur = Utilisateur::find($id);
        if (!$utilisateur) {
            Log::error("Utilisateur introuvable", ['id' => $id]);
            return response()->json(['success' => false, 'message' => __('Utilisateur introuvable.')], 404);
        }

        Log::info("Utilisateur trouvé", ['utilisateur' => $utilisateur]);

        // Récupérer les données envoyées depuis le frontend
        $dateDepart = $request->input('date_depart');
        $equipements = $request->input('equipements', []);
        $commentaire = $request->input('commentaire');

        Log::info("Données reçues", [
            'date_depart' => $dateDepart,
            'equipements' => $equipements,
            'commentaire' => $commentaire
        ]);

        if (!$dateDepart) {
            Log::error("Date de départ manquante");
            return response()->json(['success' => false, 'message' => __('Date de départ manquante.')], 422);
        }

        try {
            // Exemple de log avant chaque étape
            Log::info("Début de la gestion des équipements");
            Equipement::retourEquipements($equipements);

            Log::info("Retour des équipements réussi", ['equipements' => $equipements]);

            Log::info("Récupération des lignes associées");
            $lignes = Affectation::where('id_utilisateur', $id)
                ->whereNotNull('id_ligne')
                ->with('ligne', 'ligne.forfait', 'ligne.operateur')
                ->get();

            Log::info("Lignes récupérées", ['lignes' => $lignes]);

            $idLignes = $lignes->pluck('ligne.id_ligne')->toArray();

            Log::info("Résiliation des lignes", ['id_lignes' => $idLignes]);
            Ligne::resilierLignes($idLignes);

            Log::info("Clôture des affectations");
            Affectation::cloturerAffectationsUtilisateur($id, $dateDepart, $commentaire);

            Log::info("Suppression logique de l'utilisateur");
            $utilisateur->deleted_at = $dateDepart;
            $utilisateur->save();

            Log::info("Utilisateur supprimé avec succès");
            return response()->json(['success' => true, 'message' => __('Utilisateur supprimé avec succès.')]);
        } catch (Exception $e) {
            Log::error("Erreur lors de la suppression de l'utilisateur", [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Erreur lors de la suppression de l\'utilisateur.')
            ], 500);
        }
    }

    
    // Recherche des fonctions
    public function searchFonction(Request $request)
    {
        try {
            $term = $request->input('query');

            // Si aucun terme n'est fourni, retourner une réponse vide
            if (empty($term)) {
                return response()->json([], 200);
            }

            // Rechercher les fonctions correspondantes
            $fonctions = Fonction::where('fonction', 'ILIKE', "%{$term}%")
                ->get()
                ->map(function ($fonction) {
                    return [
                        'id' => $fonction->id_fonction,
                        'label' => $fonction->fonction
                    ];
                });

            return response()->json($fonctions, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    // Recherche des chantiers
    public function searchChantier(Request $request)
    {
        try {
            $term = $request->input('query');

            // Si aucun terme n'est fourni, retourner une réponse vide
            if (empty($term)) {
                return response()->json([], 200);
            }

            // Appeler la méthode du modèle
            $chantiers = Localisation::searchByTerm($term);

            return response()->json($chantiers, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ATTRIBUTION EQUIPEMENT
    public function showPhonesInactifs()
    {
        $phonesInactifs = Equipement::phonesInactif();
        return response()->json($phonesInactifs);
    }

    public function showBoxInactifs()
    {
        $boxInactifs = Equipement::boxInactif();
        return response()->json($boxInactifs);
    }

    public function rechercherInactifs(Request $request)
    {
        $type = $request->input('type'); // 'phones' ou 'box'
        $searchTerm = $request->input('searchTerm', '');

        if ($type === 'phones') {
            $resultats = Equipement::recherchePhonesInactifs($searchTerm);
        } elseif ($type === 'box') {
            $resultats = Equipement::rechercheBoxInactifs($searchTerm);
        } else {
            return response()->json(['error' => 'Type invalide'], 400);
        }
        return response()->json($resultats);
    }
    public function rechercherLigneInactifs(Request $request)
    {
        // Récupérer les paramètres de recherche
        $operateurId = $request->get('operateur');
        $searchTerm = $request->get('searchTerm');

        // Requête pour les deux vues : `view_ligne_inactif` et `view_ligne_en_attente`
        $resultats = DB::table('view_ligne_inactif')
            ->where('id_operateur', $operateurId)
            ->where(function ($query) use ($searchTerm) {
                $query->where('num_ligne', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('num_sim', 'LIKE', "%{$searchTerm}%");
            })
            ->union(
                DB::table('view_ligne_en_attente')
                    ->where('id_operateur', $operateurId)
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('num_ligne', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('num_sim', 'LIKE', "%{$searchTerm}%");
                    })
            )
            ->get();

        // Retourner les résultats au format JSON
        return response()->json($resultats);
    }

    public function attrEquipement(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_utilisateur_attr' => 'required|integer|exists:utilisateur,id_utilisateur',
                'id_equipement_attr' => 'required|integer|exists:equipement,id_equipement',
                'date_attr' => 'required|date',
            ]);

            Affectation::attrEquipement(
                $validated['id_utilisateur_attr'],
                $validated['id_equipement_attr'],
                $validated['date_attr']
            );

            Equipement::attrEquipement($validated['id_equipement_attr']);
            
            return redirect()->route('ref.user')->with('success', 'Équipement attribué avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('ref.user')->withErrors(['attr_equipement_errors' => $e->getMessage()]);
        } catch (Exception $e) {
            return redirect()->route('ref.user')->withErrors(['attr_equipement_errors' => 'Une erreur est survenue.']);
        }
    }

    public function attrLigne(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_utilisateur_attr_ligne' => 'required|integer|exists:utilisateur,id_utilisateur',
                'id_ligne_attr_ligne' => 'required|integer|exists:ligne,id_ligne',
                'date_attr_ligne' => 'required|date',
            ]);

            Affectation::attrLigne(
                $validated['id_utilisateur_attr_ligne'],
                $validated['id_ligne_attr_ligne'],
                $validated['date_attr_ligne']
            );

            Ligne::attrLigne($validated['id_ligne_attr_ligne']);
            
            return redirect()->route('ref.user')->with('success', 'Ligne attribué avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('ref.user')->withErrors(['attr_ligne_errors' => $e->getMessage()]);
        } catch (Exception $e) {
            return redirect()->route('ref.user')->withErrors(['attr_ligne_errors' => 'Une erreur est survenue.']);
        }
    }

    // Histo User
    public function histoUser($id_user)
    {
        $histoUser = Utilisateur::getHistoriqueUtilisateur($id_user);

        // Retourne un tableau vide si aucun historique n'est trouvé
        if (empty($histoUser)) {
            return response()->json([]);
        }

        return response()->json($histoUser);
    }

    // Equipements affectés DEPART
    public function equipementsAffectes($id_user)
    {
        // Vérifie si l'utilisateur existe
        $utilisateurExists = DB::table('utilisateur')
            ->where('id_utilisateur', $id_user)
            ->exists();

        if (!$utilisateurExists) {
            return response()->json(['success' => false, 'message' => __('Utilisateur introuvable.')], 404);
        }

        // Récupère uniquement les équipements affectés à l'utilisateur
        $sqlEquipements = "
            SELECT 
                id_utilisateur, marque, modele, type_equipement, imei, serial_number, debut_affectation, fin_affectation, id_equipement
            FROM view_historique_user_equipement 
            WHERE id_utilisateur = :id_utilisateur AND fin_affectation IS NULL
        ";

        $equipements = DB::select($sqlEquipements, ['id_utilisateur' => $id_user]);

        return response()->json(['success' => true, 'equipements' => $equipements]);
    }
    
    // Lignes affectés DEPART
    public function lignesAffectes($id_user)
    {
        // Vérifier si l'utilisateur existe
        $utilisateurExists = DB::table('utilisateur')
            ->where('id_utilisateur', $id_user)
            ->exists();
    
        if (!$utilisateurExists) {
            return response()->json(['success' => false, 'message' => __('Utilisateur introuvable.')], 404);
        }
    
        // Récupérer les lignes associées à l'utilisateur (actives)
        $sqllignes = "
            SELECT 
                *
            FROM view_historique_user_ligne 
            WHERE id_utilisateur = :id_utilisateur AND fin_affectation IS NULL
        ";
    
        $lignes = DB::select($sqllignes, ['id_utilisateur' => $id_user]);
    
        // Répondre avec les lignes associées
        return response()->json(['success' => true, 'lignes' => $lignes]);
    }

}