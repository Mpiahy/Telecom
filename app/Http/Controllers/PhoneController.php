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

class PhoneController extends Controller
{
    public function phoneView(Request $request)
    {
        $login = Session::get('login');

        $marques = Marque::marquePhone()->get();
        $modeles = Modele::modelePhone()->get();
        $statuts = StatutEquipement::all();
        $types = TypeEquipement::forPhones()->get();

        if ($request->has('reset_filters')) {
            return redirect()->route('ref.phone');
        }

        $filters = $request->only(['filter_marque', 'filter_statut', 'search_imei', 'search_sn', 'search_user']);

        $equipements = Equipement::getPhonesWithDetails($filters, "5");

        return view('ref.phone', compact(
            'login', 'marques', 'modeles', 'statuts', 'types', 'equipements', 'filters'
        ));
    }

    public function savePhone(Request $request)
    {
        try {
            // Valider les données
            $validatedData = $request->validate([
                'enr_phone_type' => 'required|exists:type_equipement,id_type_equipement',
                'enr_phone_marque' => 'required',
                'new_phone_marque' => 'required_if:enr_phone_marque,new_marque|max:50',
                'enr_phone_modele' => 'required',
                'new_phone_modele' => 'required_if:enr_phone_modele,new|max:50',
                'enr_phone_imei' => 'required|unique:equipement,imei|max:50',
                'enr_phone_sn' => 'required|unique:equipement,serial_number|max:50',
                'enr_phone_enroll' => 'required|in:1,2',
            ]);

            // Gestion des marques et modèles
            $marque = Marque::findOrCreate($request->enr_phone_marque, $request->new_phone_marque, $request->enr_phone_type);
            $modele = Modele::findOrCreate($request->enr_phone_modele, $request->new_phone_modele, $marque->id_marque);

            // Créer l'équipement
            Equipement::createPhoneFromRequest($validatedData, $modele);

            // Retour succès
            return redirect()->route('ref.phone')->with('success', 'Téléphone enregistré avec succès.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.phone')
                ->withErrors($e->errors(), 'enr_phone_errors') // Associer les erreurs à enr_phone_errors
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.phone')
                ->withErrors(['error_general' => $e->getMessage()])
                ->withInput();
        }
    }

    public function updatePhone(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'edt_phone_imei' => 'required|unique:equipement,imei,' . $id . ',id_equipement',
                'edt_phone_sn' => 'required|unique:equipement,serial_number,' . $id . ',id_equipement',
                'edt_phone_enroll' => 'required|in:1,2',
            ]);            

            // Trouver l'équipement existant
            $equipement = Equipement::findOrFail($id);

            // Mettre à jour l'équipement
            $equipement->updatePhoneFromRequest($validatedData);

            return redirect()->route('ref.phone')->with('success', 'Téléphone modifié avec succès.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.phone')
                ->withErrors($e->errors(), 'edt_phone_errors') // Associer les erreurs à edt_phone_errors
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.phone')
                ->withErrors(['error_general' => $e->getMessage()])
                ->withInput();
        }
    }

    public function hsPhone(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phone_id' => 'required|exists:equipement,id_equipement',
            ]);

            $equipement = Equipement::findOrFail($validatedData['phone_id']);

            Affectation::hsEquipement($equipement->id_equipement);

            StatutEquipement::markAsHS($equipement);

            // Message de succès
            return redirect()
                ->route('ref.phone')
                ->with('success', "Le téléphone {$equipement->modele->marque->marque} {$equipement->modele->nom_modele} ({$equipement->serial_number}) a été marqué comme HS.");
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

    public function retourPhone(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'retour_phone_id' => 'required|exists:equipement,id_equipement',
                'retour_affectation_id' => 'required|exists:affectation,id_affectation',
                'retour_date' => 'required|date',
                'retour_statut' => 'required|integer|in:3,4', // Assure que le statut est soit "Retourné" (3) soit "HS" (4)
                'retour_commentaire' => 'nullable|string|max:500',
            ]);

            $affectation = Affectation::findOrFail($validatedData['retour_affectation_id']);
            $affectation->retourAffectationEquipement($validatedData['retour_date'], $validatedData['retour_commentaire']);

            $equipement = Equipement::findOrFail($validatedData['retour_phone_id']);
            $equipement->retourEquipement($validatedData['retour_statut']);

            return redirect()
                ->route('ref.phone')
                ->with('success', "Le téléphone {$equipement->modele->marque->marque} {$equipement->modele->nom_modele} ({$equipement->serial_number}) a été retourné avec succès.");
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors(), 'retour_phone_errors')
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error_general' => 'Une erreur est survenue : ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Récupérer les marques par type d'équipement
    public function getMarquesByType($typeId)
    {
        $marques = Marque::getByType($typeId);

        return response()->json([
            'success' => true,
            'marques' => $marques
        ]);
    }

    // Récupérer les modèles par marque
    public function getModelesByMarque($marqueId)
    {
        $modeles = Modele::getByMarque($marqueId);

        return response()->json([
            'success' => true,
            'modeles' => $modeles
        ]);
    }

    // Histo Phone
    public function histoPhone($id_phone)
    {
        $histoPhone = Equipement::getHistoriqueEquipement($id_phone);

        // Retourne un tableau vide si aucun historique n'est trouvé
        if (empty($histoPhone)) {
            return response()->json([]);
        }

        return response()->json($histoPhone);
    }
}
