<?php

namespace App\Http\Controllers;

use App\Exports\SuiviEquipementExport;
use App\Exports\SuiviFlotteExport;
use App\Models\Affectation;
use App\Models\Equipement;
use App\Models\Ligne;
use App\Exports\TableauDeBordExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\DateHelper;
use App\Services\FlotteService;
use App\Services\EquipementService;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Affiche la vue du tableau de bord avec les données.
     */
    public function indexView(Request $request)
    {
        // Récupération de l'année depuis les paramètres GET
        $annee = $request->input('annee', date('Y'));
        $mois = $request->input('mois', null);

        // Vérifier si l'utilisateur est authentifié
        $user = Auth::user();

        // Définir le rôle
        $role = $user->isAdmin ? 'admin' : 'guest';

        // Charger des données différentes en fonction du rôle
        $data = $this->getDashboardData($annee, $mois);

        // Ajouter des informations utilisateur et rôle pour la vue
        $data['login'] = $user->login;
        $data['role'] = $role;

        return view('index', $data);
    }

    /**
     * Filtre les données du tableau de bord et recharge la vue.
     */
    public function filterDashboard(Request $request)
    {
        $annee = $request->input('annee', date('Y'));

        // Rediriger avec les données filtrées (utilisation des paramètres GET)
        return redirect()->route('index', ['annee' => $annee]);
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $data = $this->prepareExportData($annee);

        $moisFrancais = DateHelper::getMoisFrancais(); // Appel de la méthode depuis le helper
        $pdf = Pdf::loadView('pdf.dashboard', compact('annee', 'data', 'moisFrancais'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("tableau_de_bord_telecom_$annee.pdf");
    }

    /**
     * Export des données en XLSX.
     */
    public function exportXLSX(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        return Excel::download(new TableauDeBordExport($annee), "tableau_de_bord_telecom_$annee.xlsx");
    }

    /**
     * Récupère les données pour le tableau de bord.
     */
    private function getDashboardData($selectedYear, $selectedMonth)
    {
        $login = Session::get('login');

        // Données sur les lignes et équipements
        $ligneStats = Ligne::getStats();
        $equipementStats = Equipement::getStats();

        // Données de facturation
        if ($selectedMonth) {
            $totalPrixForfaitHT = Affectation::getTbdMoisAnnee($selectedYear, $selectedMonth);
            $monthlyData = null;
        } else {
            $totalPrixForfaitHT = null;
            $monthlyData = Affectation::getTbdAnnee($selectedYear);
        }

        return array_merge(
            $ligneStats,
            $equipementStats,
            compact('login', 'selectedYear', 'selectedMonth', 'monthlyData', 'totalPrixForfaitHT')
        );
    }

    /**
     * Prépare les données pour l'export (PDF, XLSX).
     */
    private function prepareExportData($annee)
    {
        return Affectation::getYearlyData($annee);
    }

    private $flotteService;
    private $equipementService;

    public function __construct(FlotteService $flotteService, EquipementService $equipementService)
    {
        $this->flotteService = $flotteService;
        $this->equipementService = $equipementService;
    }

    public function exportSuiviFlotte(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        return Excel::download(
            new SuiviFlotteExport($annee, $this->flotteService),
            "suivi_flotte_$annee.xlsx"
        );
    }

    public function exportEquipement()
    {
        $fileName = 'suivi_equipement_telecom.xlsx';
        return Excel::download(
            new SuiviEquipementExport($this->equipementService),
            $fileName
        );
    }

}
