<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Equipement;
use App\Models\Marque;
use App\Models\Modele;
use App\Models\TypeEquipement;
use App\Models\StatutEquipement;
use Illuminate\Validation\ValidationException;

class BoxController extends Controller
{
    public function boxView(Request $request)
    {
        $login = Session::get('login');

        $marques = Marque::marqueBox()->get();
        $modeles = Modele::modeleBox()->get();
        $statuts = StatutEquipement::all();
        $types = TypeEquipement::forBox()->get();
    
        if ($request->has('reset_filters')) {
            return redirect()->route('ref.box');
        }
    
        $filters = $request->only(['filter_marque', 'filter_statut', 'search_imei', 'search_sn']);
    
        $equipements = Equipement::getBoxWithDetails($filters, "5");
            
        return view('ref.box', compact(
            'login', 'marques', 'modeles', 'statuts', 'types', 'equipements', 'filters'
        ));
    }

    public function saveBox(Request $request)
    {
        try {
            // Valider les données
            $validatedData = $request->validate([
                'enr_box_marque' => 'required',
                'new_box_marque' => 'required_if:enr_box_marque,new_marque|max:50',
                'enr_box_modele' => 'required',
                'new_box_modele' => 'required_if:enr_box_modele,new|max:50',
                'enr_box_imei' => 'required|unique:equipement,imei|max:50',
                'enr_box_sn' => 'required|unique:equipement,serial_number|max:50',
            ]);
    
            // Gestion des marques et modèles
            $marque = Marque::findOrCreate($request->enr_box_marque, $request->new_box_marque, 3);
            $modele = Modele::findOrCreate($request->enr_box_modele, $request->new_box_modele, $marque->id_marque);
    
            // Créer l'équipement
            Equipement::createBoxFromRequest($validatedData, $modele);
    
            // Retour succès
            return redirect()->route('ref.box')->with('success', 'Box enregistré avec succès.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.box')
                ->withErrors($e->errors(), 'enr_box_errors') // Associer les erreurs à enr_box_errors
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.box')
                ->withErrors(['error_general' => $e->getMessage()])
                ->withInput();
        }
    }

    public function updateBox(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'edt_box_imei' => 'required|unique:equipement,imei,' . $id . ',id_equipement',
                'edt_box_sn' => 'required|unique:equipement,serial_number,' . $id . ',id_equipement',
            ]);

            // Trouver l'équipement existant
            $equipement = Equipement::findOrFail($id);

            // Mettre à jour l'équipement
            $equipement->updateBoxFromRequest($validatedData);

            return redirect()->route('ref.box')->with('success', 'Box modifié avec succès.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.box')
                ->withErrors($e->errors(), 'edt_box_errors') // Associer les erreurs à edt_box_errors
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.box')
                ->withErrors(['error_general' => $e->getMessage()])
                ->withInput();
        }
    }

    public function hsBox(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'box_id' => 'required|exists:equipement,id_equipement',
            ]);

            $equipement = Equipement::findOrFail($validatedData['box_id']);

            Affectation::hsEquipement($equipement->id_equipement);

            StatutEquipement::markAsHS($equipement);

            // Message de succès
            return redirect()
                ->route('ref.box')
                ->with('success', "Le box {$equipement->modele->marque->marque} {$equipement->modele->nom_modele} ({$equipement->serial_number}) a été marqué comme HS.");
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error_general' => 'Une erreur est survenue : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function retourBox(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'retour_box_id' => 'required|exists:equipement,id_equipement',
                'retour_affectation_id' => 'required|exists:affectation,id_affectation',
                'retour_date' => 'required|date',
            ]);

            $affectation = Affectation::findOrFail($validatedData['retour_affectation_id']);

            $affectation->retourAffectationEquipement($validatedData['retour_date']);

            $equipement = Equipement::findOrFail($validatedData['retour_box_id']);
            $equipement->retourEquipement();

            return redirect()
                ->route('ref.box')
                ->with('success', "La box {$equipement->modele->marque->marque} {$equipement->modele->nom_modele} ({$equipement->serial_number}) a été retournée.");
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors(),'retour_box_errors')
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error_general' => 'Une erreur est survenue : ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Historique Box
    public function histoBox($id_box)
    {
        $histoBox = Equipement::getHistoriqueEquipement($id_box);

        // Retourne un tableau vide si aucun historique n'est trouvé
        if (empty($histoBox)) {
            return response()->json([]);
        }

        return response()->json($histoBox);
    }

}
