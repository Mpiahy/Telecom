<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Import;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    /**
     * Affiche la vue d'importation.
     */
    public function importView()
    {
        $login = Session::get('login');
        return view('import.import', compact('login'));
    }

    /**
     * Traite le fichier CSV envoyé.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            Log::info('Début du traitement du fichier CSV.');

            // Sauvegarde temporaire du fichier
            $filePath = $request->file('csv_file')->store('imports');
            $fileFullPath = storage_path('app/' . $filePath);

            // Extraction et filtrage des données CSV
            $filteredData = Import::processCSV($fileFullPath);

            if (empty($filteredData)) {
                Log::warning('Aucune donnée valide dans le fichier.');
                return redirect()->route('import.view')->with('error', 'Aucune donnée valide trouvée.');
            }

            // Importation en batch
            $lignesImportées = Import::batchInsert($filteredData);
            $totalLignes = count($filteredData);

            Log::info("Importation terminée : $lignesImportées/$totalLignes lignes insérées.");

            return redirect()->route('import.view')->with('success', "$lignesImportées lignes ont été importées avec succès !");
        } catch (\Exception $e) {
            Log::error('Erreur critique lors de l\'importation : ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->route('import.view')->with('error', 'Une erreur est survenue pendant l\'importation.');
        }
    }

    /**
     * Traite le fichier CSV envoyé.
     */
    public function equipementImport(Request $request)
    {
        $request->validate([
            'csv_file_equipement' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            Log::info('Début du traitement du fichier CSV.');

            // Sauvegarde temporaire du fichier
            $filePath = $request->file('csv_file_equipement')->store('imports');
            $fileFullPath = storage_path('app/' . $filePath);

            // Extraction et filtrage des données CSV
            $filteredData = Import::equipementCSV($fileFullPath);

            if (empty($filteredData)) {
                Log::warning('Aucune donnée valide dans le fichier.');
                return redirect()->route('import.view')->with('error', 'Aucune donnée valide trouvée.');
            }

            // Importation en batch
            $lignesImportées = Import::batchInsertEquipement($filteredData);
            $totalLignes = count($filteredData);

            Log::info("Importation terminée : $lignesImportées/$totalLignes lignes insérées.");

            return redirect()->route('import.view')->with('success', "$lignesImportées lignes ont été importées avec succès !");
        } catch (\Exception $e) {
            Log::error('Erreur critique lors de l\'importation : ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->route('import.view')->with('error', 'Une erreur est survenue pendant l\'importation.');
        }
    }
}
