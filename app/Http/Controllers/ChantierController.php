<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Service;
use App\Models\Localisation;
use Exception;

class ChantierController extends Controller
{
    public function chantierView(Request $request)
    {
        $login = Session::get('login');

        // Récupérer tous les UE pour afficher les filtres dynamiques
        $services = Service::all();

        // Vérifier si le bouton "Tout" a été cliqué (reset des filtres)
        if ($request->has('reset_filters') && $request->input('reset_filters') == 'reset') {
            // Réinitialiser tous les filtres
            $filters = [
                'service' => null,
                'search_chantier' => null,
                'search_imputation' => null,
            ];
        } else {
            // Appliquer les filtres existants
            $filters = [
                'service' => $request->input('service'),
                'search_chantier' => $request->input('search_chantier'),
                'search_imputation' => $request->input('search_imputation'),
            ];
        }

        // Utiliser les méthodes du modèle pour appliquer les filtres
        $localisations = Localisation::with(['service', 'imputation'])
            ->filterByService($filters['service'])
            ->filterByImputation($filters['search_imputation'])
            ->searchByTerm($filters['search_chantier'])
            ->paginate(5);

        // Passer les données à la vue, y compris les filtres actifs
        return view('ref.chantier', compact('login', 'services', 'localisations', 'filters'));
    }

    /**
     * Ajoute une localisation en utilisant un service existant.
     */
    public function ajouterChantier(Request $request)
    {
        try {
            $validated = $request->validate([
                'add_service' => 'required|integer|exists:service,id_service',
                'add_imp' => 'required|string|max:255',
            ]);
    
            Localisation::createWithRelations($validated);
    
            return redirect()->route('ref.chantier')->with('success', 'Localisation ajoutée avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Concaténer les erreurs pour le toast
            $errorMessage = 'Erreur de validation : ' . implode(', ', array_map(function ($errors) {
                return implode(', ', $errors);
            }, $e->errors()));
    
            return redirect()
                ->route('ref.chantier')
                ->with('error', $errorMessage) // Afficher les erreurs dans un toast
                ->withErrors($e->errors(), 'add_chantier_errors') // Garder les erreurs pour le formulaire
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.chantier')
                ->with('error', 'Une erreur inattendue est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }    

    /**
     * Modifie une localisation existante.
     */
    public function modifierChantier(Request $request, $id)
    {
        $validated = $request->validate([
            'edt_service' => 'required|integer|exists:service,id_service', // id_service doit exister
            'edt_imp' => 'required|string|max:255',
        ]);

        try {
            // Trouve la localisation et met à jour ses relations
            $localisation = Localisation::findOrFail($id);
            $localisation->updateWithRelations($validated);

            return redirect()->route('ref.chantier')->with('success', 'Localisation mise à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Concaténer les erreurs pour le toast
            $errorMessage = 'Erreur de validation : ' . implode(', ', array_map(function ($errors) {
                return implode(', ', $errors);
            }, $e->errors()));
    
            return redirect()
                ->route('ref.chantier')
                ->with('error', $errorMessage) // Afficher les erreurs dans un toast
                ->withErrors($e->errors(), 'edt_chantier_errors') // Garder les erreurs pour le formulaire
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->route('ref.chantier')
                ->with('error', 'Une erreur inattendue est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime une localisation en vérifiant les relations.
     */
    public function supprimerChantier($id)
    {
        try {
            // Appelle la méthode du modèle pour supprimer une localisation
            $localisation = Localisation::findOrFail($id);
            $localisation->deleteWithRelations();

            return redirect()->route('ref.chantier')->with('success', 'Localisation supprimée avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('ref.chantier')
                ->with('modal_del_errors', $e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('ref.chantier')->with('error', "Une erreur est survenue. Veuillez réessayer.");
        }
    }

}
