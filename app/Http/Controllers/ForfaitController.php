<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Forfait;
use App\Models\ForfaitElement;
use App\Models\Element;
use App\Models\Operateur;
use App\Models\TypeForfait;
use Illuminate\Validation\ValidationException;

class ForfaitController extends Controller
{
    /**
     * Affiche la vue des forfaits avec les détails d'un forfait sélectionné.
     */
    public function forfaitView(Request $request)
    {
        $login = Session::get('login');
        
        // Obtenir les filtres actifs depuis la requête
        $filters = $request->only(['filter_type_forfait', 'filter_operateur']);

        // Appliquer les filtres pour récupérer les forfaits
        $forfaits = Forfait::getFilteredForfaits($filters);

        $types_forfait = TypeForfait::all();
        $operateurs = Operateur::all();

        // Récupérer l'ID du forfait sélectionné ou prendre le premier forfait par défaut
        $selectedForfaitId = $request->get('forfait') ?? $forfaits->first()?->id_forfait;

        $forfaitDetails = null;
        $elements = null;

        if ($selectedForfaitId) {
            // Utiliser la méthode statique pour récupérer les détails et les éléments
            $forfaitData = Forfait::getForfaitWithDetails($selectedForfaitId);

            if ($forfaitData) {
                $forfaitDetails = $forfaitData['details'];
                $elements = $forfaitData['elements'];
            }
        }

        // Retourner la vue avec les données nécessaires
        return view('ref.forfait', compact(
            'login',
            'forfaits',
            'forfaitDetails',
            'elements',
            'selectedForfaitId',
            'types_forfait',
            'operateurs'
        ));
    }

    /**
     * Met à jour les éléments d'un forfait.
     *
     * @param Request $request
     * @param int $id_forfait
     * @param int $id_element
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateElement(Request $request, $id_forfait, $id_element)
    {
        try {
            $validatedData = $request->validate([
                'edt_element' => 'required|string|max:255',
                'edt_unite' => 'required|string|max:50',
                'edt_qu' => 'required|numeric|min:0',
                'edt_pu' => 'required|numeric|min:0',
                'edt_id_element' => 'required|exists:element,id_element',
                'edt_id_forfait' => 'required|exists:forfait,id_forfait'
            ]);            

            $forfaitElementQuantite = new ForfaitElement();
            $forfaitElementQuantite->updateQuantiteFromRequest($validatedData, $id_element, $id_forfait);// Appeler la méthode de mise à jour dans le modèle
            
            $elementPrix = new Element();
            $elementPrix->updatePrixElementFromRequest($validatedData, $id_element);

            return back()->with('success', 'L’élément a été mis à jour avec succès.');
            
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.forfait', ['forfait' => $id_forfait])
                ->withErrors($e->errors(), 'edt_element_errors')
                ->with('openEditModal', true)
                ->withInput();
        
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.forfait', ['forfait' => $id_forfait])
                ->withErrors(['error_general' => $e->getMessage()])
                ->with('openEditModal', true)
                ->withInput();
        }
    }

    /**
     * Met à jour les éléments d'un forfait.
     *
     * @param Request $request
     * @param int $id_forfait
     * @param int $id_element
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteElement(Request $request, $id_forfait, $id_element)
    {
        try {
            $validatedData = $request->validate([
                'del_element' => 'required|string|max:255',
                'del_unite' => 'required|string|max:50',
                'del_qu' => 'required|numeric|min:0',
                'del_pu' => 'required|numeric|min:0',
                'del_id_element' => 'required|exists:element,id_element',
                'del_id_forfait' => 'required|exists:forfait,id_forfait'
            ]);            

            // Forcer la valeur 'del_qu' à 0
            $validatedData['del_qu'] = 0;

            $forfaitElementQuantite = new ForfaitElement();
            $forfaitElementQuantite->deleteQuantiteFromRequest($validatedData, $id_element, $id_forfait);// Appeler la méthode de mise à jour dans le modèle

            return back()->with('success', 'L’élément a été supprimé avec succès.');
            
        } catch (ValidationException $e) {
            return redirect()
                ->route('ref.forfait', ['forfait' => $id_forfait])
                ->withErrors($e->errors(), 'del_element_errors')
                ->with('openDeleteModal', true)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('ref.forfait', ['forfait' => $id_forfait])
                ->withErrors(['error_general' => $e->getMessage()])
                ->with('openDeleteModal', true)
                ->withInput();
        }
    }
}