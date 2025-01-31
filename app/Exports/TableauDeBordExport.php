<?php

namespace App\Exports;

use App\Helpers\DateHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TableauDeBordExport implements FromArray, WithHeadings, WithStyles, WithCustomStartCell, ShouldAutoSize, WithTitle
{
    protected $annee;

    public function __construct($annee)
    {
        $this->annee = $annee;
    }

    /**
     * Définit la cellule de démarrage pour le contenu.
     */
    public function startCell(): string
    {
        return 'A3'; // Commence à la cellule A3, laissant la place pour le titre.
    }

    /**
     * Génère les données pour le tableau Excel.
     */
    public function array(): array
    {
        $annee = $this->annee;
        $data = \App\Models\Affectation::getYearlyData($annee);

        $output = [];
        foreach ($data as $type => $values) {
            $row = [$type];

            for ($mois = 1; $mois <= 12; $mois++) {
                // Récupérer la valeur correcte
                $montant = $values[$mois]['total_prix_forfait_ht'] ?? 0;
                $row[] = number_format($montant, 2, ',', ' ');
            }

            // Total annuel
            $totalAnnuel = $values['total_annuel'] ?? 0;
            $row[] = number_format($totalAnnuel, 2, ',', ' ');

            $output[] = $row;
        }

        return $output;
    }

    /**
     * Définit les en-têtes des colonnes.
     */
    public function headings(): array
    {
        $moisFrancais = DateHelper::getMoisFrancais(); // Appel de la méthode depuis le helper
        return array_merge(['Type ligne'], array_values($moisFrancais), ['Total Année']);
    }

    /**
     * Applique les styles au fichier Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // Appliquer un style au titre principal
        $sheet->mergeCells('A1:O1'); // Fusionner les cellules pour le titre (A à O pour inclure toutes les colonnes)
        $sheet->setCellValue('A1', 'Tableau de bord Telecom ' . $this->annee); // Ajouter le titre
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16); // Titre en gras et plus grand
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Centrer le titre

        // Appliquer un style aux en-têtes des colonnes
        $sheet->getStyle('A3:N3')->getFont()->setBold(true); // En-têtes en gras
        $sheet->getStyle('A3:N3')->getAlignment()->setHorizontal('center'); // Centrer les textes des en-têtes
        $sheet->getStyle('A3:N3')->getFill()->setFillType('solid'); // Remplissage solide
        $sheet->getStyle('A3:N3')->getFill()->getStartColor()->setARGB('FFDDDDDD'); // Couleur grise claire pour les en-têtes

        // Ajuster automatiquement la largeur des colonnes
        foreach (range('A', 'O') as $col) { // Inclure toutes les colonnes utilisées (A à O)
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    /**
     * Définit le titre de la feuille Excel.
     */
    public function title(): string
    {
        return (string)$this->annee; // Le titre de la feuille sera l'année sélectionnée
    }
}
